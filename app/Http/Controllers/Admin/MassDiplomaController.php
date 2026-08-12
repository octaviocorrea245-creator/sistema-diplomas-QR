<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotifySupervisors;
use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\User;
use App\Models\Firmante;
use App\Models\FirmaAuditoria;
use App\Services\PdfGenerator;
use App\Services\PdfSigner;
use App\Services\QrGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class MassDiplomaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    private function departamentoId()
    {
        return auth()->user()->department_id;
    }

    public function create(Request $request)
    {
        $cursoId = $request->query('curso_id');

        $cursos = Cursos::where('departamento_id', $this->departamentoId())
            ->whereIn('estado', ['activo', 'finalizado'])
            ->orderBy('nombre')
            ->get();

        $templates = DiplomaTemplate::whereHas('curso', fn($q) =>
            $q->where('departamento_id', $this->departamentoId())
        )->with('curso')->orderBy('nombre')->get();

        $firmantes = Firmante::where('departamento_id', $this->departamentoId())
            ->disponibles()
            ->orderBy('nombre')
            ->get();

        $cursoPreseleccionado = null;
        $templatePreseleccionado = null;
        if ($cursoId) {
            $cursoPreseleccionado = $cursos->find($cursoId);
            if ($cursoPreseleccionado) {
                $templatePreseleccionado = $templates->firstWhere('curso_id', $cursoId);
            }
        }

        return view('admin.diplomas.mass-create', compact('cursos', 'templates', 'firmantes', 'cursoPreseleccionado', 'templatePreseleccionado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id'      => 'required|exists:cursos,id',
            'template_id'   => 'required|exists:diploma_templates,id',
            'fecha_emision' => 'required|date',
            'firmante_id'   => 'nullable|exists:firmantes,id',
            'password_firma'=> 'nullable|string|required_with:firmante_id',
        ]);

        $curso = Cursos::findOrFail($data['curso_id']);
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $template = DiplomaTemplate::findOrFail($data['template_id']);
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);
        if (!$template->elements()->exists()) {
            return redirect()->route('admin.cursos.show', $curso->id)
                ->with('toast', ['type' => 'error', 'message' => 'La plantilla no tiene elementos. Diseñala primero.']);
        }

        // Prepare digital signature params (optional)
        $signatureParams = null;
        $firmante = null;
        if (!empty($data['firmante_id'])) {
            $firmante = Firmante::findOrFail($data['firmante_id']);
            abort_unless($firmante->departamento_id === $this->departamentoId(), 403);
            try {
                $signatureParams = app(PdfSigner::class)->prepareSignatureParams($firmante, $data['password_firma']);
            } catch (\Exception $e) {
                return back()->withErrors(['password_firma' => 'Error con la firma electrónica: ' . $e->getMessage()]);
            }
        }

        $alumnos = $curso->alumnos()
            ->wherePivotIn('estado', ['inscrito', 'en_curso', 'completado'])
            ->orderBy('full_name')
            ->get();

        if ($alumnos->isEmpty()) {
            return back()->withErrors(['curso_id' => 'No hay alumnos inscritos en este curso.']);
        }

        $already = Diploma::where('curso_id', $curso->id)
            ->whereIn('user_id', $alumnos->pluck('id'))
            ->pluck('user_id')
            ->toArray();

        $generated = [];
        $skipped = 0;
        $firmaErrors = 0;

        $qrGen  = app(QrGenerator::class);
        $pdfGen = app(PdfGenerator::class);

        foreach ($alumnos as $alumno) {
            if (in_array($alumno->id, $already)) {
                $skipped++;
                continue;
            }

            $folio = 'DIP-' . strtoupper(Str::random(8));
            while (Diploma::where('folio', $folio)->exists()) {
                $folio = 'DIP-' . strtoupper(Str::random(8));
            }

            $tokenQr = (string) Str::uuid();

            $diplomaData = [
                'user_id'              => $alumno->id,
                'curso_id'             => $curso->id,
                'version_plantilla_id' => null,
                'template_id'          => $template->id,
                'emitido_por'          => auth()->id(),
                'folio'                => $folio,
                'token_qr'             => $tokenQr,
                'ruta_pdf'             => 'diplomas/' . $folio . '.pdf',
                'fecha_emision'        => $data['fecha_emision'],
                'estado'               => 'emitido',
            ];

            if ($firmante) {
                $diplomaData['firmante_id']          = $firmante->id;
                $diplomaData['firmado_en']           = now();
                $diplomaData['tiene_firma_digital']  = true;
                $diplomaData['cert_serie_usada']     = $signatureParams['serie'] ?? null;
            }

            $diploma = Diploma::create($diplomaData);

            // QR image
            $qrGen->generate(route('verificar', $tokenQr), 'qr/' . $tokenQr . '.png');

            // PDF (with or without signature)
            $pdfContent = $pdfGen->generate($template, $diploma, $signatureParams);

            \Illuminate\Support\Facades\Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

            // Audit trail for digital signature
            if ($firmante && $signatureParams) {
                FirmaAuditoria::create([
                    'diploma_id'  => $diploma->id,
                    'firmante_id' => $firmante->id,
                    'usuario_id'  => auth()->id(),
                    'accion'      => 'firmado',
                    'cert_serie'  => $signatureParams['serie'] ?? null,
                    'ip'          => request()->ip(),
                ]);
            }

            $generated[] = $diploma;
        }

        $count = count($generated);
        $msg = "{$count} diploma(s) generado(s)" . ($firmante ? ' y firmado(s) electrónicamente' : '') . '.';
        if ($skipped > 0) {
            $msg .= " {$skipped} alumno(s) ya tenían diploma.";
        }

        if ($count > 0) {
            NotifySupervisors::send(
                $curso->departamento_id,
                'diploma_emitido',
                "{$count} diploma(s) emitido(s) para el curso {$curso->nombre}.",
                route('supervisor.cursos.show', $curso->id)
            );
        }

        return redirect()->route('admin.cursos.show', $curso->id)
            ->with('toast', ['type' => 'success', 'message' => $msg]);
    }

    public function downloadCombined(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $template = $curso->template;
        abort_unless($template, 404, 'El curso no tiene plantilla de diploma.');

        $diplomas = Diploma::where('curso_id', $curso->id)
            ->where('template_id', $template->id)
            ->with(['alumno', 'template.elements'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($diplomas->isEmpty()) {
            return back()->with('error', 'No hay diplomas para este curso.');
        }

        $pdfGen = app(PdfGenerator::class);
        $pdfContent = $pdfGen->generateMultiple($template, $diplomas);

        if (empty($pdfContent)) {
            return back()->with('error', 'No se pudieron renderizar los diplomas.');
        }

        $nombre = 'diplomas-' . Str::slug($curso->nombre) . '-' . date('Ymd') . '.pdf';
        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
        ]);
    }

    public function show(Request $request, Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('curso_id', $curso->id)
            ->where('template_id', $request->template)
            ->with(['alumno', 'template', 'firmante'])
            ->orderBy('created_at', 'desc')
            ->get();

        $template = DiplomaTemplate::findOrFail($request->template);

        return view('admin.diplomas.mass-show', compact('curso', 'template', 'diplomas'));
    }

    public function download(Diploma $diploma)
    {
        abort_unless($diploma->curso->departamento_id === $this->departamentoId(), 403);

        $path = 'public/' . $diploma->ruta_pdf;
        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        return response()->stream(function () use ($path) {
            echo \Illuminate\Support\Facades\Storage::read($path);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="diploma-' . $diploma->folio . '.pdf"',
        ]);
    }

    public function generateIndividual(Cursos $curso, User $alumno)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);
        abort_unless($alumno->department_id === $this->departamentoId(), 403);

        $exists = Diploma::where('curso_id', $curso->id)->where('user_id', $alumno->id)->exists();
        if ($exists) {
            return back()->with('toast', ['type' => 'error', 'message' => "{$alumno->full_name} ya tiene un diploma."]);
        }

        $template = $curso->template;
        if (!$template) {
            return back()->with('toast', ['type' => 'error', 'message' => 'El curso no tiene plantilla de diploma.']);
        }

        $folio = 'DIP-' . strtoupper(Str::random(8));
        while (Diploma::where('folio', $folio)->exists()) {
            $folio = 'DIP-' . strtoupper(Str::random(8));
        }

        $tokenQr = (string) Str::uuid();

        $diploma = Diploma::create([
            'user_id'              => $alumno->id,
            'curso_id'             => $curso->id,
            'version_plantilla_id' => null,
            'template_id'          => $template->id,
            'emitido_por'          => auth()->id(),
            'folio'                => $folio,
            'token_qr'             => $tokenQr,
            'ruta_pdf'             => 'diplomas/' . $folio . '.pdf',
            'fecha_emision'        => now(),
            'estado'               => 'emitido',
        ]);

        $qrGen = app(QrGenerator::class);
        $qrGen->generate(route('verificar', $tokenQr), 'qr/' . $tokenQr . '.png');

        $pdfGen = app(PdfGenerator::class);
        $pdfContent = $pdfGen->generate($template, $diploma);
        Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

        return back()->with('toast', [
            'type'    => 'success',
            'message' => "Diploma {$folio} generado para {$alumno->full_name}.",
        ]);
    }

    public function regenerateIndividual(Diploma $diploma)
    {
        abort_unless($diploma->curso->departamento_id === $this->departamentoId(), 403);

        $template = $diploma->template ?? $diploma->curso->template;
        if (!$template) {
            return back()->with('toast', ['type' => 'error', 'message' => 'No se encontró la plantilla del diploma.']);
        }

        $oldPdf = 'public/' . $diploma->ruta_pdf;
        if (Storage::exists($oldPdf)) {
            Storage::delete($oldPdf);
        }

        $pdfGen = app(PdfGenerator::class);
        $pdfContent = $pdfGen->generate($template, $diploma);
        Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

        $diploma->update(['estado' => 'reemitido']);

        NotifySupervisors::send(
            $diploma->curso->departamento_id,
            'diploma_reemitido',
            "Diploma {$diploma->folio} reemitido para {$diploma->alumno->full_name} en {$diploma->curso->nombre}.",
            route('supervisor.cursos.show', $diploma->curso_id)
        );

        return back()->with('toast', [
            'type'    => 'success',
            'message' => "Diploma {$diploma->folio} regenerado correctamente (QR intacto).",
        ]);
    }

    public function regenerate(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('curso_id', $curso->id)->get();
        $template = $curso->template;

        if (!$template) {
            return back()->with('toast', ['type' => 'error', 'message' => 'El curso no tiene plantilla de diploma.']);
        }

        if ($diplomas->isEmpty()) {
            return back()->with('toast', ['type' => 'info', 'message' => 'No hay diplomas para regenerar.']);
        }

        $pdfGen = app(PdfGenerator::class);
        $count = 0;
        $errors = 0;

        foreach ($diplomas as $d) {
            $oldPdf = 'public/' . $d->ruta_pdf;
            if (Storage::exists($oldPdf)) {
                Storage::delete($oldPdf);
            }

            try {
                $pdfContent = $pdfGen->generate($template, $d);
                Storage::disk('public')->put($d->ruta_pdf, $pdfContent);
                $d->update(['estado' => 'reemitido']);
                $count++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $msg = "{$count} diploma(s) regenerados correctamente (QR intacto).";
        if ($errors > 0) {
            $msg .= " {$errors} error(es).";
        }

        if ($count > 0) {
            NotifySupervisors::send(
                $curso->departamento_id,
                'diploma_reemitido',
                "{$count} diploma(s) reemitido(s) para el curso {$curso->nombre}.",
                route('supervisor.cursos.show', $curso->id)
            );
        }

        return redirect()->route('admin.cursos.show', $curso->id)
            ->with('toast', ['type' => 'success', 'message' => $msg]);
    }

    public function downloadAll(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('curso_id', $curso->id)
            ->with('alumno')
            ->get();

        if ($diplomas->isEmpty()) {
            return back()->with('error', 'No hay diplomas para este curso.');
        }

        $zipName = 'diplomas-' . Str::slug($curso->nombre) . '-' . date('Ymd') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);
        \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/temp'));

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo crear el ZIP.');
        }

        $added = 0;
        foreach ($diplomas as $diploma) {
            $filePath = storage_path('app/public/' . $diploma->ruta_pdf);
            if (file_exists($filePath)) {
                $safeName = Str::slug($diploma->alumno?->full_name ?? 'alumno') . '-' . $diploma->folio . '.pdf';
                $zip->addFile($filePath, $safeName);
                $added++;
            }
        }

        $zip->close();

        if ($added === 0 || !file_exists($zipPath) || filesize($zipPath) < 22) {
            // Limpiar archivo vacío si quedó
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
            return back()->with('error',
                'No se encontraron archivos PDF en disco. Usa el botón "Regenerar" para volver a generarlos.'
            );
        }

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

    public function generateQuick(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $template = $curso->template;
        if (!$template) {
            return back()->with('toast', ['type' => 'error', 'message' => 'El curso no tiene plantilla de diploma.']);
        }
        if (!$template->elements()->exists()) {
            return back()->with('toast', ['type' => 'error', 'message' => 'La plantilla no tiene elementos. Diseñala primero.']);
        }

        $alumnos = $curso->alumnos()
            ->wherePivotIn('estado', ['inscrito', 'en_curso', 'completado'])
            ->orderBy('full_name')
            ->get();

        if ($alumnos->isEmpty()) {
            return back()->with('toast', ['type' => 'error', 'message' => 'No hay alumnos inscritos en este curso.']);
        }

        $already = Diploma::where('curso_id', $curso->id)
            ->whereIn('user_id', $alumnos->pluck('id'))
            ->pluck('user_id')
            ->toArray();

        $generated = [];
        $skipped = 0;

        $qrGen  = app(QrGenerator::class);
        $pdfGen = app(PdfGenerator::class);
        $fecha  = now()->format('Y-m-d');

        DB::beginTransaction();
        try {
            foreach ($alumnos as $alumno) {
                if (in_array($alumno->id, $already)) {
                    $skipped++;
                    continue;
                }

                $folio = 'DIP-' . strtoupper(Str::random(8));
                while (Diploma::where('folio', $folio)->exists()) {
                    $folio = 'DIP-' . strtoupper(Str::random(8));
                }

                $tokenQr = (string) Str::uuid();

                $diploma = Diploma::create([
                    'user_id'              => $alumno->id,
                    'curso_id'             => $curso->id,
                    'version_plantilla_id' => null,
                    'template_id'          => $template->id,
                    'emitido_por'          => auth()->id(),
                    'folio'                => $folio,
                    'token_qr'             => $tokenQr,
                    'ruta_pdf'             => 'diplomas/' . $folio . '.pdf',
                    'fecha_emision'        => $fecha,
                    'estado'               => 'emitido',
                ]);

                $qrGen->generate(route('verificar', $tokenQr), 'qr/' . $tokenQr . '.png');

                $pdfContent = $pdfGen->generate($template, $diploma);
                Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

                $generated[] = $diploma;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast', ['type' => 'error', 'message' => 'Error al generar diplomas: ' . $e->getMessage()]);
        }

        $count = count($generated);
        $msg = "{$count} diploma(s) generado(s) correctamente.";
        if ($skipped > 0) {
            $msg .= " {$skipped} alumno(s) ya tenían diploma.";
        }

        if ($count > 0) {
            NotifySupervisors::send(
                $curso->departamento_id,
                'diploma_emitido',
                "{$count} diploma(s) emitido(s) para el curso {$curso->nombre}.",
                route('supervisor.cursos.show', $curso->id)
            );
        }

        return redirect()->route('admin.cursos.show', $curso->id)
            ->with('toast', ['type' => 'success', 'message' => $msg]);
    }
}
