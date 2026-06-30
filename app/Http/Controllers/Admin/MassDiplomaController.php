<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\User;
use App\Services\DiplomaRenderer;
use App\Services\QrGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

        $cursoPreseleccionado = null;
        $templatePreseleccionado = null;
        if ($cursoId) {
            $cursoPreseleccionado = $cursos->find($cursoId);
            if ($cursoPreseleccionado) {
                $templatePreseleccionado = $templates->firstWhere('curso_id', $cursoId);
            }
        }

        return view('admin.diplomas.mass-create', compact('cursos', 'templates', 'cursoPreseleccionado', 'templatePreseleccionado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id'    => 'required|exists:cursos,id',
            'template_id' => 'required|exists:diploma_templates,id',
            'fecha_emision' => 'required|date',
        ]);

        $curso = Cursos::findOrFail($data['curso_id']);
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $template = DiplomaTemplate::findOrFail($data['template_id']);
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);
        if (!$template->elements()->exists()) {
            return redirect()->route('admin.cursos.show', $curso->id)
                ->with('toast', ['type' => 'error', 'message' => 'La plantilla no tiene elementos. Diseñala primero.']);
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

        $renderer = app(DiplomaRenderer::class);
        $qrGen = app(QrGenerator::class);

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
                'user_id'             => $alumno->id,
                'curso_id'            => $curso->id,
                'version_plantilla_id' => null,
                'template_id'         => $template->id,
                'emitido_por'         => auth()->id(),
                'folio'               => $folio,
                'token_qr'            => $tokenQr,
                'ruta_pdf'            => 'diplomas/' . $folio . '.pdf',
                'fecha_emision'       => $data['fecha_emision'],
                'estado'              => 'emitido',
            ]);

            // Generate QR
            $qrGen->generate(route('verificar', $tokenQr), 'qr/' . $tokenQr . '.png');

            // Generate PDF
            $html = $renderer->renderHtml($template, $diploma, true);
            $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
            $pdfContent = $pdf->output();

            $diploma->ruta_pdf = 'diplomas/' . $folio . '.pdf';
            \Illuminate\Support\Facades\Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);
            $diploma->save();

            $generated[] = $diploma;
        }

        $count = count($generated);
        $msg = "{$count} diploma(s) generado(s) correctamente.";
        if ($skipped > 0) {
            $msg .= " {$skipped} alumno(s) ya tenían diploma.";
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

        $renderer = app(DiplomaRenderer::class);
        $allContent = '';
        foreach ($diplomas as $i => $d) {
            if ($d->template && $d->template->elements->isNotEmpty()) {
                $content = $renderer->renderContent($d->template, $d, true);
                $allContent .= '<div style="page-break-after:' . ($i < $diplomas->count() - 1 ? 'always' : 'avoid') . ';">' . $content . '</div>';
            }
        }

        if (empty($allContent)) {
            return back()->with('error', 'No se pudieron renderizar los diplomas.');
        }

        $html = <<<HTML
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
  @page { margin: 0; size: 841.89pt 595.28pt; }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { margin:0; }
  .el { position: absolute; overflow: hidden; }
</style>
</head><body>
{$allContent}
</body></html>
HTML;
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $nombre = 'diplomas-' . Str::slug($curso->nombre) . '-' . date('Ymd') . '.pdf';
        return $pdf->download($nombre);
    }

    public function show(Request $request, Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('curso_id', $curso->id)
            ->where('template_id', $request->template)
            ->with(['alumno', 'template'])
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

    public function regenerate(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('curso_id', $curso->id)->get();

        foreach ($diplomas as $d) {
            $pdfPath = 'public/' . $d->ruta_pdf;
            if (\Illuminate\Support\Facades\Storage::exists($pdfPath)) {
                \Illuminate\Support\Facades\Storage::delete($pdfPath);
            }
            $qrPath = 'public/qr/' . $d->token_qr . '.png';
            if (\Illuminate\Support\Facades\Storage::exists($qrPath)) {
                \Illuminate\Support\Facades\Storage::delete($qrPath);
            }
            $d->delete();
        }

        return redirect()->route('admin.diplomas.mass.create', ['curso_id' => $curso->id])
            ->with('success', "Diplomas eliminados. Genera los nuevos a continuación.");
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
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            return back()->with('error', 'No se pudo crear el ZIP.');
        }

        foreach ($diplomas as $diploma) {
            $filePath = storage_path('app/public/' . $diploma->ruta_pdf);
            if (file_exists($filePath)) {
                $safeName = Str::slug($diploma->alumno?->full_name ?? 'alumno') . '-' . $diploma->folio . '.pdf';
                $zip->addFile($filePath, $safeName);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
