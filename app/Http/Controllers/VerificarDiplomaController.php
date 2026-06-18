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

        $diplomaHtml = null;
        if ($diploma->template && $diploma->template->elements->isNotEmpty()) {
            $renderer = app(DiplomaRenderer::class);
            $diplomaHtml = $renderer->renderHtml($diploma->template, $diploma);
        }

        return view('public.verificar', compact('diploma', 'diplomaHtml'));
    }

    public function imagen(string $token)
    {
        $diploma = Diploma::where('token_qr', $token)
            ->with(['alumno', 'curso', 'template.elements'])
            ->firstOrFail();

        abort_unless($diploma->template && $diploma->template->elements->isNotEmpty(), 404, 'No hay plantilla');

        $renderer = app(DiplomaRenderer::class);
        $html = $renderer->renderHtml($diploma->template, $diploma);

        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, $diploma->template->canvas_width, $diploma->template->canvas_height]);
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
        $html = $renderer->renderHtml($diploma->template, $diploma);

        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, $diploma->template->canvas_width, $diploma->template->canvas_height]);
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download('diploma-' . $diploma->folio . '.pdf');
    }
}
