<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diploma;
use App\Models\Firmante;
use App\Models\FirmaAuditoria;
use App\Services\PdfGenerator;
use App\Services\PdfSigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FirmanteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    private function departamentoId(): int
    {
        return auth()->user()->department_id;
    }

    // ─── CRUD ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $firmantes = Firmante::where('departamento_id', $this->departamentoId())
            ->orderByDesc('activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.firmantes.index', compact('firmantes'));
    }

    public function create()
    {
        return view('admin.firmantes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'cargo'    => 'required|string|max:255',
            'cer_file' => 'required|file|mimes:cer|max:512',
            'key_file' => 'required|file|max:512',
        ], [
            'cer_file.mimes' => 'El archivo debe ser un certificado .cer',
            'cer_file.max'   => 'El certificado no debe superar 512 KB',
            'key_file.max'   => 'La llave privada no debe superar 512 KB',
        ]);

        $deptId = $this->departamentoId();
        $dir    = "firmantes/{$deptId}";

        // Guarda los archivos en storage/app/private/ (fuera del acceso web)
        $cerPath = $request->file('cer_file')->storeAs($dir, uniqid('cert_') . '.cer', 'private');
        $keyPath = $request->file('key_file')->storeAs($dir, uniqid('key_')  . '.key', 'private');

        // Extrae datos del certificado
        $signer   = app(PdfSigner::class);
        $certInfo = [];
        try {
            $certInfo = $signer->parseCertificate(storage_path("app/private/{$cerPath}"));
        } catch (\Throwable $e) {
            // Si no se puede leer el .cer, igual se guarda el firmante sin esos datos
            logger()->warning('FirmanteController@store: no se pudo parsear .cer — ' . $e->getMessage());
        }

        Firmante::create([
            'departamento_id'    => $deptId,
            'nombre'             => $data['nombre'],
            'cargo'              => $data['cargo'],
            'cer_path'           => $cerPath,
            'key_path'           => $keyPath,
            'rfc'                => $certInfo['rfc']                ?? null,
            'certificado_numero' => $certInfo['certificado_numero'] ?? null,
            'cert_valido_desde'  => $certInfo['valido_desde']       ?? null,
            'cert_expira_en'     => $certInfo['expira_en']          ?? null,
            'activo'             => true,
        ]);

        return redirect()->route('admin.firmantes.index')
            ->with('toast', ['type' => 'success', 'message' => 'Firmante registrado correctamente.']);
    }

    public function show(Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        $diplomas = Diploma::where('firmante_id', $firmante->id)
            ->with('alumno', 'curso')
            ->orderByDesc('firmado_en')
            ->paginate(15);

        $auditorias = \App\Models\FirmaAuditoria::where('firmante_id', $firmante->id)
            ->with('diploma.alumno', 'usuario')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.firmantes.show', compact('firmante', 'diplomas', 'auditorias'));
    }

    public function edit(Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        return view('admin.firmantes.edit', compact('firmante'));
    }

    public function update(Request $request, Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'cargo'    => 'required|string|max:255',
            'cer_file' => 'nullable|file|mimes:cer|max:512',
            'key_file' => 'nullable|file|max:512',
            'activo'   => 'boolean',
        ]);

        $updates = [
            'nombre' => $data['nombre'],
            'cargo'  => $data['cargo'],
            'activo' => $request->boolean('activo', $firmante->activo),
        ];

        $signer = app(PdfSigner::class);
        $deptId = $this->departamentoId();
        $dir    = "firmantes/{$deptId}";

        // Reemplaza el .cer si se subió uno nuevo
        if ($request->hasFile('cer_file')) {
            Storage::disk('private')->delete($firmante->cer_path);
            $cerPath = $request->file('cer_file')->storeAs($dir, uniqid('cert_') . '.cer', 'private');

            $certInfo = [];
            try {
                $certInfo = $signer->parseCertificate(storage_path("app/private/{$cerPath}"));
            } catch (\Throwable $e) {
                logger()->warning('FirmanteController@update: no se pudo parsear .cer — ' . $e->getMessage());
            }

            $updates['cer_path']           = $cerPath;
            $updates['rfc']                = $certInfo['rfc']                ?? $firmante->rfc;
            $updates['certificado_numero'] = $certInfo['certificado_numero'] ?? $firmante->certificado_numero;
            $updates['cert_valido_desde']  = $certInfo['valido_desde']       ?? $firmante->cert_valido_desde;
            $updates['cert_expira_en']     = $certInfo['expira_en']          ?? $firmante->cert_expira_en;
        }

        // Reemplaza el .key si se subió uno nuevo
        if ($request->hasFile('key_file')) {
            Storage::disk('private')->delete($firmante->key_path);
            $updates['key_path'] = $request->file('key_file')
                ->storeAs($dir, uniqid('key_') . '.key', 'private');
        }

        $firmante->update($updates);

        return redirect()->route('admin.firmantes.index')
            ->with('toast', ['type' => 'success', 'message' => 'Firmante actualizado.']);
    }

    public function destroy(Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        // No se puede eliminar si ya firmó diplomas
        if ($firmante->diplomas()->exists()) {
            return back()->with('toast', [
                'type'    => 'error',
                'message' => 'No se puede eliminar: este firmante ya tiene diplomas firmados.',
            ]);
        }

        Storage::disk('private')->delete([$firmante->cer_path, $firmante->key_path]);
        $firmante->delete();

        return redirect()->route('admin.firmantes.index')
            ->with('toast', ['type' => 'success', 'message' => 'Firmante eliminado.']);
    }

    public function toggleActivo(Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        $firmante->update(['activo' => !$firmante->activo]);

        $estado = $firmante->activo ? 'activado' : 'desactivado';

        return back()->with('toast', ['type' => 'success', 'message' => "Firmante {$estado}."]);
    }

    // ─── Firma de diplomas ─────────────────────────────────────────────────────

    /**
     * Muestra el formulario para firmar un diploma con este firmante.
     * GET /admin/firmantes/{firmante}/firmar/{diploma}
     */
    public function firmarForm(Firmante $firmante, Diploma $diploma)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);
        abort_unless($diploma->curso->departamento_id === $this->departamentoId(), 403);

        if ($diploma->estaFirmado()) {
            return back()->with('toast', ['type' => 'info', 'message' => 'Este diploma ya tiene firma digital.']);
        }

        return view('admin.firmantes.firmar', compact('firmante', 'diploma'));
    }

    /**
     * Aplica la firma digital al PDF del diploma.
     * POST /admin/firmantes/{firmante}/firmar/{diploma}
     */
    public function firmar(Request $request, Firmante $firmante, Diploma $diploma)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);
        abort_unless($diploma->curso->departamento_id === $this->departamentoId(), 403);

        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'La contraseña de la e.firma es obligatoria.',
        ]);

        $signer = app(PdfSigner::class);

        // 1. Prepara el certificado (valida vigencia + descifra llave)
        try {
            $sigParams = $signer->prepareSignatureParams($firmante, $request->input('password'));
        } catch (\Throwable $e) {
            return back()->with('toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        // 2. Genera el PDF firmado
        $diploma->load(['alumno', 'curso', 'template.elements']);

        if (!$diploma->template || $diploma->template->elements->isEmpty()) {
            return back()->with('toast', ['type' => 'error', 'message' => 'El diploma no tiene plantilla o elementos.']);
        }

        try {
            $pdfContent = app(PdfGenerator::class)->generate(
                $diploma->template,
                $diploma,
                $sigParams
            );
        } catch (\Throwable $e) {
            logger()->error('FirmanteController@firmar PDF error: ' . $e->getMessage());
            return back()->with('toast', ['type' => 'error', 'message' => 'Error al generar el PDF firmado.']);
        }

        // 3. Guarda el PDF firmado sobreescribiendo el anterior
        Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

        // 4. Actualiza el registro del diploma
        $diploma->update([
            'firmante_id'        => $firmante->id,
            'firmado_en'         => now(),
            'tiene_firma_digital' => true,
            'cert_serie_usada'   => $sigParams['serie'],
        ]);

        // 5. Registra en auditoría
        FirmaAuditoria::create([
            'diploma_id'  => $diploma->id,
            'firmante_id' => $firmante->id,
            'usuario_id'  => auth()->id(),
            'accion'      => 'individual',
            'cert_serie'  => $sigParams['serie'],
            'ip'          => request()->ip(),
        ]);

        return redirect()
            ->route('admin.diplomas.mass.show', ['curso' => $diploma->curso_id, 'template' => $diploma->template_id])
            ->with('toast', ['type' => 'success', 'message' => "Diploma {$diploma->folio} firmado digitalmente."]);
    }

    /**
     * Firma todos los diplomas de un curso que aún no tienen firma.
     * POST /admin/firmantes/{firmante}/firmar-masivo
     */
    public function firmarMasivo(Request $request, Firmante $firmante)
    {
        abort_unless($firmante->departamento_id === $this->departamentoId(), 403);

        $request->validate([
            'password' => 'required|string',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $signer = app(PdfSigner::class);

        try {
            $sigParams = $signer->prepareSignatureParams($firmante, $request->input('password'));
        } catch (\Throwable $e) {
            return back()->with('toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        $diplomas = Diploma::where('curso_id', $request->curso_id)
            ->where('tiene_firma_digital', false)
            ->with(['alumno', 'curso', 'template.elements'])
            ->get();

        if ($diplomas->isEmpty()) {
            return back()->with('toast', ['type' => 'info', 'message' => 'Todos los diplomas de este curso ya están firmados.']);
        }

        $pdfGen  = app(PdfGenerator::class);
        $firmados = 0;
        $errores  = 0;

        foreach ($diplomas as $diploma) {
            if (!$diploma->template || $diploma->template->elements->isEmpty()) {
                $errores++;
                continue;
            }

            try {
                $pdfContent = $pdfGen->generate($diploma->template, $diploma, $sigParams);
                Storage::disk('public')->put($diploma->ruta_pdf, $pdfContent);

                $diploma->update([
                    'firmante_id'         => $firmante->id,
                    'firmado_en'          => now(),
                    'tiene_firma_digital' => true,
                    'cert_serie_usada'    => $sigParams['serie'],
                ]);

                FirmaAuditoria::create([
                    'diploma_id'  => $diploma->id,
                    'firmante_id' => $firmante->id,
                    'usuario_id'  => auth()->id(),
                    'accion'      => 'masivo',
                    'cert_serie'  => $sigParams['serie'],
                    'ip'          => request()->ip(),
                ]);

                $firmados++;
            } catch (\Throwable $e) {
                logger()->error("FirmanteController@firmarMasivo diploma {$diploma->id}: " . $e->getMessage());
                $errores++;
            }
        }

        $msg = "{$firmados} diploma(s) firmado(s) correctamente.";
        if ($errores > 0) {
            $msg .= " {$errores} con error (ver logs).";
        }

        return back()->with('toast', ['type' => 'success', 'message' => $msg]);
    }
}
