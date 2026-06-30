<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Services\DiplomaRenderer;
use Barryvdh\DomPDF\Facade\Pdf;

class VerificarDiplomaController extends Controller
{
    public function show(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso.departamento', 'versionPlantilla.plantilla', 'emisor', 'template.elements'])
            ->firstOrFail();

        $sessionKey = 'verificado_' . $diploma->id;
        $ultimaVisita = session($sessionKey);
        $sesionValida = $ultimaVisita && now()->diffInMinutes($ultimaVisita) < 10;

        session([$sessionKey => now()]);

        $tiempoRestante = $sesionValida
            ? max(0, 600 - now()->diffInSeconds($ultimaVisita))
            : 600;

        $diplomaHtml = null;
        if ($diploma->template && $diploma->template->elements->isNotEmpty()) {
            $renderer = app(DiplomaRenderer::class);
            $diplomaHtml = $renderer->renderHtml($diploma->template, $diploma, false);
        }

        return view('public.verificar', compact('diploma', 'diplomaHtml', 'sesionValida', 'tiempoRestante'));
    }

    public function imagen(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso', 'template.elements'])
            ->firstOrFail();

        abort_unless($diploma->template && $diploma->template->elements->isNotEmpty(), 404, 'No hay plantilla');

        $renderer = app(DiplomaRenderer::class);
        $html = $renderer->renderHtml($diploma->template, $diploma, true);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream('diploma-' . $diploma->folio . '.pdf');
    }

    public function pdf(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso', 'template.elements'])
            ->firstOrFail();

        abort_unless($diploma->template && $diploma->template->elements->isNotEmpty(), 404, 'No hay plantilla');

        $renderer = app(DiplomaRenderer::class);
        $html = $renderer->renderHtml($diploma->template, $diploma, true);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download('diploma-' . $diploma->folio . '.pdf');
    }
}
