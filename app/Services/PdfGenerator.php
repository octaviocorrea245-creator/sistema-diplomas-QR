<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\DiplomaTemplateElement;
use TCPDF;

class PdfGenerator
{
    // Dimensiones A4 landscape en puntos (pt)
    const PAGE_W = 841.89;
    const PAGE_H = 595.28;

    /**
     * Genera el PDF de un diploma y devuelve el contenido binario.
     *
     * @param array|null $signatureParams  Resultado de PdfSigner::prepareSignatureParams()
     *                                     ['cert_pem', 'key_resource', 'serie']
     */
    public function generate(DiplomaTemplate $template, Diploma $diploma, ?array $signatureParams = null): string
    {
        $pdf    = $this->createPdf();
        $scaleX = self::PAGE_W / $template->canvas_width;
        $scaleY = self::PAGE_H / $template->canvas_height;

        // setSignature() debe llamarse ANTES de AddPage()
        if ($signatureParams) {
            $this->applySignature($pdf, $signatureParams);
        }

        $pdf->AddPage();
        $this->drawBackground($pdf, $template);
        $this->drawElements($pdf, $template, $diploma, $scaleX, $scaleY);

        return $pdf->Output('', 'S');
    }

    /**
     * Genera un PDF con múltiples diplomas (una página por diploma).
     * No soporta firma digital (la firma aplica a todo el doc; usar generate() por diploma).
     */
    public function generateMultiple(DiplomaTemplate $template, iterable $diplomas): string
    {
        $pdf    = $this->createPdf();
        $scaleX = self::PAGE_W / $template->canvas_width;
        $scaleY = self::PAGE_H / $template->canvas_height;

        foreach ($diplomas as $diploma) {
            $pdf->AddPage();
            $this->drawBackground($pdf, $template);
            $this->drawElements($pdf, $template, $diploma, $scaleX, $scaleY);
        }

        return $pdf->Output('', 'S');
    }

    // ─── privados ─────────────────────────────────────────────────────────────

    /**
     * Aplica la firma digital al objeto TCPDF.
     * Debe llamarse ANTES de AddPage().
     */
    private function applySignature(TCPDF $pdf, array $params): void
    {
        // TCPDF setSignature(cert, key, keypass, extracerts, certtype, info)
        // certtype 2 = certificado de firma (PKCS7 detached)
        $pdf->setSignature(
            $params['cert_pem'],
            $params['key_resource'],
            '',         // keypass: ya desbloqueamos la llave en PdfSigner
            '',         // extracerts (cadena de CA, opcional)
            2,          // certtype
            [
                'Name'        => 'UPGP DiplomadoQR',
                'Reason'      => 'Diploma con firma electrónica',
                'Location'    => 'México',
                'ContactInfo' => 'upgp.edu.mx',
            ]
        );
    }

    private function createPdf(): TCPDF
    {
        // TCPDF 6.x necesita K_PATH_FONTS apuntando a su directorio de fuentes
        if (!defined('K_PATH_MAIN')) {
            define('K_PATH_MAIN', base_path('vendor/tecnickcom/tcpdf/'));
        }
        if (!defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', base_path('vendor/tecnickcom/tcpdf/fonts/'));
        }
        if (!defined('K_PATH_CACHE')) {
            $cacheDir = storage_path('app/tcpdf_cache/');
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            define('K_PATH_CACHE', $cacheDir);
        }

        $pdf = new TCPDF('L', 'pt', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('DiplomadoQR - UPGP');
        $pdf->SetAuthor('UPGP');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setCellPaddings(0, 0, 0, 0);

        return $pdf;
    }

    private function drawBackground(TCPDF $pdf, DiplomaTemplate $template): void
    {
        if (!$template->background_image) {
            return;
        }
        $path = public_path($template->background_image);
        if (file_exists($path)) {
            $pdf->Image($path, 0, 0, self::PAGE_W, self::PAGE_H, '', '', '', false, 300, '', false, false, 0);
        }
    }

    private function drawElements(TCPDF $pdf, DiplomaTemplate $template, Diploma $diploma, float $scaleX, float $scaleY): void
    {
        $qrUrl = route('verificar', $diploma->token_qr);

        foreach ($template->elements as $el) {
            $this->drawElement($pdf, $el, $diploma, $qrUrl, $scaleX, $scaleY);
        }
    }

    private function drawElement(TCPDF $pdf, DiplomaTemplateElement $el, Diploma $diploma, string $qrUrl, float $scaleX, float $scaleY): void
    {
        $config = $el->config_json;
        if (is_string($config)) {
            $config = json_decode($config, true) ?? [];
        } elseif (!is_array($config)) {
            $config = [];
        }

        $x = round($el->x * $scaleX, 3);
        $y = round($el->y * $scaleY, 3);
        $w = round($el->width  * $scaleX, 3);
        $h = round($el->height * $scaleY, 3);

        switch ($el->tipo) {

            case 'text':
            case 'variable':
                $text = $el->tipo === 'text'
                    ? ($config['text'] ?? '')
                    : $this->resolveVariable($el->variable, $diploma);

                // Reemplaza {{ variable }} dentro del texto
                $text = preg_replace_callback('/\{\{(\w+)\}\}/', function ($m) use ($diploma) {
                    return $this->resolveVariable($m[1], $diploma);
                }, $text);

                $fontSize = max(1, round(($config['fontSize'] ?? 24) * $scaleX, 1));
                $color    = $this->hexToRgb($config['fill'] ?? '#000000');
                $align    = strtoupper(substr($config['textAlign'] ?? 'left', 0, 1)); // L/C/R/J
                $style    = '';
                if (!empty($config['bold']))   $style .= 'B';
                if (!empty($config['italic'])) $style .= 'I';

                $pdf->SetFont('helvetica', $style, $fontSize);
                $pdf->SetTextColor($color[0], $color[1], $color[2]);
                // MultiCell con texto centrado verticalmente en el bloque
                $pdf->MultiCell(
                    $w, $h, $text,
                    0,      // border
                    $align, // align
                    false,  // fill
                    1,      // ln (next line below)
                    $x, $y, // position
                    true,   // reset height
                    0,      // stretch
                    false,  // ishtml
                    true,   // autopadding
                    $h,     // max height
                    'M'     // valign middle
                );
                break;

            case 'qr':
                $style = [
                    'border'  => false,
                    'padding' => 0,
                    'fgcolor' => [0, 0, 0],
                    'bgcolor' => false,
                ];
                $pdf->write2DBarcode($qrUrl, 'QRCODE,M', $x, $y, $w, $h, $style, 'N');
                break;

            case 'rect':
                $fill    = $config['fill']        ?? 'transparent';
                $stroke  = $config['stroke']      ?? '#000000';
                $strokeW = ($config['strokeWidth'] ?? 1) * $scaleX;

                $sColor = $this->hexToRgb($stroke);
                $pdf->SetDrawColor($sColor[0], $sColor[1], $sColor[2]);
                $pdf->SetLineWidth(max(0.1, $strokeW));

                $hasFill = $fill !== 'transparent' && $fill !== 'none' && $fill !== '';
                if ($hasFill) {
                    $fColor = $this->hexToRgb($fill);
                    $pdf->SetFillColor($fColor[0], $fColor[1], $fColor[2]);
                    $pdf->Rect($x, $y, $w, $h, 'DF');
                } else {
                    $pdf->Rect($x, $y, $w, $h, 'D');
                }
                break;

            case 'line':
                $stroke  = $config['stroke']       ?? '#000000';
                $strokeW = ($config['strokeWidth']  ?? 2) * $scaleX;

                $sColor = $this->hexToRgb($stroke);
                $pdf->SetDrawColor($sColor[0], $sColor[1], $sColor[2]);
                $pdf->SetLineWidth(max(0.1, $strokeW));
                $pdf->Line($x, $y, $x + $w, $y + $h);
                break;

            case 'image':
                // 'path' guardado desde el editor (relativo a public/)
                $relPath = $config['path'] ?? null;
                if ($relPath) {
                    $imgPath = public_path($relPath);
                    if (file_exists($imgPath)) {
                        $pdf->Image($imgPath, $x, $y, $w, $h, '', '', '', false, 300, '', false, false, 0);
                    }
                }
                break;

            case 'firma':
                $firmante = $diploma->firmante;
                $nombre   = $firmante?->nombre ?? '';
                $cargo    = $firmante?->cargo  ?? '';

                $mostrarNombre = (bool)($config['mostrar_nombre'] ?? true);
                $mostrarCargo  = (bool)($config['mostrar_cargo']  ?? true);
                $fontSize      = max(6, round(($config['fontSize'] ?? 11) * $scaleX, 1));
                $color         = $this->hexToRgb($config['fill'] ?? '#1E293B');

                // Línea horizontal (la firma va sobre ella)
                $pdf->SetDrawColor($color[0], $color[1], $color[2]);
                $pdf->SetLineWidth(max(0.3, 0.5 * $scaleX));
                $lineY = $y + $h * 0.48;
                $pad   = $w * 0.04;
                $pdf->Line($x + $pad, $lineY, $x + $w - $pad, $lineY);

                // Nombre del firmante (centrado, negrita)
                $textY = $lineY + max(2, $h * 0.05);
                if ($mostrarNombre && $nombre !== '') {
                    $pdf->SetFont('helvetica', 'B', $fontSize);
                    $pdf->SetTextColor($color[0], $color[1], $color[2]);
                    $pdf->MultiCell($w, $h * 0.26, $nombre, 0, 'C', false, 1, $x, $textY, true, 0, false, true, $h * 0.26, 'M');
                    $textY += $h * 0.26;
                }

                // Cargo del firmante (centrado, normal, un punto más pequeño)
                if ($mostrarCargo && $cargo !== '') {
                    $pdf->SetFont('helvetica', '', max(5, round($fontSize * 0.85, 1)));
                    $pdf->SetTextColor($color[0], $color[1], $color[2]);
                    $pdf->MultiCell($w, $h * 0.22, $cargo, 0, 'C', false, 1, $x, $textY, true, 0, false, true, $h * 0.22, 'M');
                }
                break;
        }
    }

    private function resolveVariable(?string $variable, Diploma $diploma): string
    {
        $alumno = $diploma->alumno;
        $curso  = $diploma->curso;

        return match ($variable) {
            'full_name'        => $alumno->full_name   ?? '',
            'curso_nombre'     => $curso->nombre        ?? '',
            'curso_horas'      => (string)($curso->horas ?? ''),
            'fecha_inicio'     => $curso->fecha_inicio?->format('d/m/Y')      ?? '',
            'fecha_fin'        => $curso->fecha_fin?->format('d/m/Y')          ?? '',
            'fecha_expedicion' => $diploma->fecha_emision?->format('d/m/Y')    ?? '',
            'folio'            => $diploma->folio       ?? '',
            default            => '{{' . ($variable ?? '') . '}}',
        };
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) < 6) {
            return [0, 0, 0];
        }
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
