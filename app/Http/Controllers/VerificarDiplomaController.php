<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Services\DiplomaRenderer;
use App\Services\PdfGenerator;

class VerificarDiplomaController extends Controller
{
    public function show(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso.departamento', 'versionPlantilla.plantilla', 'emisor', 'template.elements', 'firmante'])
            ->firstOrFail();

        $sessionKey  = 'verificado_' . $diploma->id;
        $ultimaVisita = session($sessionKey);
        $sesionValida = $ultimaVisita && now()->diffInMinutes($ultimaVisita) < 10;

        session([$sessionKey => now()]);

        $tiempoRestante = $sesionValida
            ? max(0, 600 - now()->diffInSeconds($ultimaVisita))
            : 600;

        $diplomaHtml = null;
        if ($diploma->template && $diploma->template->elements->isNotEmpty()) {
            $renderer    = app(DiplomaRenderer::class);
            $diplomaHtml = $renderer->renderHtml($diploma->template, $diploma, false);
        }

        return view('public.verificar', compact('diploma', 'diplomaHtml', 'sesionValida', 'tiempoRestante'));
    }

    /**
     * Muestra el PDF del diploma en el navegador (inline).
     */
    public function imagen(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso', 'template.elements'])
            ->firstOrFail();

        abort_unless($diploma->template && $diploma->template->elements->isNotEmpty(), 404, 'No hay plantilla');

        $pdfContent = app(PdfGenerator::class)->generate($diploma->template, $diploma);

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="diploma-' . $diploma->folio . '.pdf"',
        ]);
    }

    /**
     * Descarga el PDF del diploma.
     */
    public function pdf(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso', 'template.elements'])
            ->firstOrFail();

        abort_unless($diploma->template && $diploma->template->elements->isNotEmpty(), 404, 'No hay plantilla');

        $pdfContent = app(PdfGenerator::class)->generate($diploma->template, $diploma);

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="diploma-' . $diploma->folio . '.pdf"',
        ]);
    }
}
