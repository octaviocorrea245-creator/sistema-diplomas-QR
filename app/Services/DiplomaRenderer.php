<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\DiplomaTemplateElement;

class DiplomaRenderer
{
    public function renderHtml(DiplomaTemplate $template, Diploma $diploma): string
    {
        $alumno = $diploma->alumno;
        $curso = $diploma->curso;
        $w = $template->canvas_width;
        $h = $template->canvas_height;

        $bgStyle = '';
        if ($template->background_image) {
            $bgUrl = asset('storage/' . $template->background_image);
            $bgStyle = "background: url('{$bgUrl}') no-repeat center/cover;";
        }

        $qrGenerator = app(QrGenerator::class);
        $qrBase64 = $qrGenerator->generateBase64(route('verificar', $diploma->token_qr));

        $elementsHtml = '';
        foreach ($template->elements as $el) {
            $elementsHtml .= $this->renderElement($el, $diploma, $qrBase64);
        }

        return <<<HTML
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  .diploma-wrapper {
    width: {$w}px; height: {$h}px; position: relative; overflow: hidden;
    {$bgStyle}
  }
  .diploma-wrapper .el {
    position: absolute; overflow: hidden;
  }
</style>
</head><body>
<div class="diploma-wrapper">
{$elementsHtml}
</div>
</body></html>
HTML;
    }

    private function renderElement(DiplomaTemplateElement $el, Diploma $diploma, string $qrBase64): string
    {
        $config = $el->config_json ?? [];
        $left = $el->x;
        $top = $el->y;
        $width = $el->width;
        $height = $el->height;
        $fontSize = $config['fontSize'] ?? 32;
        $color = $config['fill'] ?? '#000000';
        $align = $config['textAlign'] ?? 'left';
        $bold = !empty($config['bold']) ? 'bold' : 'normal';
        $italic = !empty($config['italic']) ? 'italic' : 'normal';

        switch ($el->tipo) {
            case 'text':
                $text = e($config['text'] ?? 'Texto');
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;
  font-size:{$fontSize}px;color:{$color};text-align:{$align};font-weight:{$bold};font-style:{$italic};">
  {$text}
</div>
HTML;

            case 'variable':
                $value = $this->resolveVariable($el->variable, $diploma);
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;
  font-size:{$fontSize}px;color:{$color};text-align:{$align};font-weight:{$bold};font-style:{$italic};">
  {$value}
</div>
HTML;

            case 'qr':
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;">
  <img src="{$qrBase64}" style="width:100%;height:100%;object-fit:contain;">
</div>
HTML;

            case 'rect':
                $fill = $config['fill'] ?? 'transparent';
                $stroke = $config['stroke'] ?? '#000000';
                $strokeW = $config['strokeWidth'] ?? 1;
                $rx = $config['rx'] ?? 0;
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;
  background:{$fill};border:{$strokeW}px solid {$stroke};border-radius:{$rx}px;">
</div>
HTML;

            case 'line':
                $stroke = $config['stroke'] ?? '#000000';
                $strokeW = $config['strokeWidth'] ?? 2;
                $x2 = $left + $width;
                $y2 = $top + $height;
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;">
  <svg width="{$width}" height="{$height}" style="overflow:visible;">
    <line x1="0" y1="0" x2="{$width}" y2="{$height}"
      stroke="{$stroke}" stroke-width="{$strokeW}" />
  </svg>
</div>
HTML;

            case 'image':
                $src = $config['src'] ?? '';
                if (!$src) return '';
                return <<<HTML
<div class="el" style="left:{$left}px;top:{$top}px;width:{$width}px;height:{$height}px;">
  <img src="{$src}" style="width:100%;height:100%;object-fit:contain;">
</div>
HTML;

            default:
                return '';
        }
    }

    private function resolveVariable(?string $variable, Diploma $diploma): string
    {
        $alumno = $diploma->alumno;
        $curso = $diploma->curso;

        return match ($variable) {
            'full_name'      => e($alumno->full_name ?? ''),
            'curso_nombre'   => e($curso->nombre ?? ''),
            'curso_horas'    => e($curso->horas ?? ''),
            'fecha_inicio'   => $curso->fecha_inicio?->format('d/m/Y') ?? '',
            'fecha_fin'      => $curso->fecha_fin?->format('d/m/Y') ?? '',
            'fecha_expedicion' => $diploma->fecha_emision?->format('d/m/Y') ?? '',
            'folio'          => e($diploma->folio ?? ''),
            default          => '{{' . e($variable ?? '') . '}}',
        };
    }
}
