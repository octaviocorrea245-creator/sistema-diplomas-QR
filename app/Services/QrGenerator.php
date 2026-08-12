<?php

namespace App\Services;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QrGenerator
{
    /**
     * Genera un QR como SVG y lo guarda en disco (storage/app/public/).
     * No requiere extensión GD.
     */
    public function generate(string $data, string $outputPath): string
    {
        $svg = $this->renderSvg($data);

        // Guardamos SVG en lugar de PNG (sin GD requerido)
        $svgPath = preg_replace('/\.png$/i', '.svg', $outputPath);
        Storage::disk('public')->put($svgPath, $svg);

        return Storage::url($svgPath);
    }

    /**
     * Genera un QR como SVG data-URI listo para usar en <img src="...">.
     * No requiere extensión GD.
     */
    public function generateBase64(string $data): string
    {
        $svg = $this->renderSvg($data);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    // ─── privado ─────────────────────────────────────────────────────────────

    private function renderSvg(string $data): string
    {
        $options = new QROptions([
            'outputInterface' => QRMarkupSVG::class,
            'eccLevel'        => EccLevel::M,
            'outputBase64'    => false,
            // Colores SVG
            'svgDefs'         => '',
            'svgOpacity'      => 1.0,
        ]);

        return (new QRCode($options))->render($data);
    }
}
