<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\DiplomaTemplateElement;

class DiplomaRenderer
{
    private function imageToBase64(string $path): string
    {
        $fullPath = public_path($path);
        if (!file_exists($fullPath)) {
            return '';
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/png',
        };
        $data = file_get_contents($fullPath);
        if ($data === false) {
            return '';
        }
        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }

    public function renderHtml(DiplomaTemplate $template, Diploma $diploma, bool $forPdf = false, bool $fullDocument = true): string
    {
        $content = $this->renderContent($template, $diploma, $forPdf);
        if (!$fullDocument) {
            return $content;
        }

        $pageCss = '';
        $bodyCss = '';
        if ($forPdf) {
            $pageCss = "@page { margin: 0; size: 841.89pt 595.28pt; }";
            $bodyCss = 'width:841.89pt;height:595.28pt;margin:0;overflow:hidden;background:#fff;';
        }

        return <<<HTML
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  {$pageCss}
  body { {$bodyCss} }
  .el { position: absolute; overflow: hidden; }
</style>
</head><body>
{$content}
</body></html>
HTML;
    }

    public function renderContent(DiplomaTemplate $template, Diploma $diploma, bool $forPdf = false): string
    {
        $w = $template->canvas_width;
        $h = $template->canvas_height;
        $unit = 'px';
        $scaleX = 1.0;
        $scaleY = 1.0;

        if ($forPdf) {
            $unit = 'pt';
            $scaleX = 841.89 / $w;
            $scaleY = 595.28 / $h;
        }

        $bgHtml = '';
        if ($template->background_image) {
            $base64 = $this->imageToBase64($template->background_image);
            if ($base64) {
                $bgHtml = '<img src="' . $base64 . '" style="position:absolute;left:0;top:0;width:100%;height:100%;object-fit:cover;pointer-events:none;">';
            }
        }

        if ($forPdf) {
            $contentStyle = "position:absolute;left:0{$unit};top:0{$unit};width:841.89{$unit};height:595.28{$unit};overflow:hidden;";
        } else {
            $contentStyle = "position:relative;width:{$w}px;height:{$h}px;overflow:hidden;";
        }

        $qrGenerator = app(QrGenerator::class);
        $qrBase64 = $qrGenerator->generateBase64(route('verificar', $diploma->token_qr));

        $elementsHtml = '';
        foreach ($template->elements as $el) {
            $elementsHtml .= $this->renderElement($el, $diploma, $qrBase64, $scaleX, $scaleY, $unit);
        }

        if ($forPdf) {
            return <<<HTML
<div style="position:relative;width:841.89{$unit};height:595.28{$unit};overflow:hidden;">
{$bgHtml}
<div style="{$contentStyle}">
{$elementsHtml}
</div>
</div>
HTML;
        }

        return <<<HTML
<div style="{$contentStyle}">
{$bgHtml}
{$elementsHtml}
</div>
HTML;
    }

    private function renderElement(DiplomaTemplateElement $el, Diploma $diploma, string $qrBase64, float $scaleX, float $scaleY, string $unit): string
    {
        $config = $el->config_json;
        if (is_string($config)) {
            $config = json_decode($config, true) ?? [];
        } elseif (is_object($config)) {
            $config = (array) $config;
        } elseif (!is_array($config)) {
            $config = [];
        }
        $left = round($el->x * $scaleX, 4);
        $top = round($el->y * $scaleY, 4);
        $width = round($el->width * $scaleX, 4);
        $height = round($el->height * $scaleY, 4);
        $fontSize = round(($config['fontSize'] ?? 32) * $scaleX, 4);
        $color = $config['fill'] ?? '#000000';
        $align = $config['textAlign'] ?? 'left';
        $bold = !empty($config['bold']) ? 'bold' : 'normal';
        $italic = !empty($config['italic']) ? 'italic' : 'normal';

        switch ($el->tipo) {
            case 'text':
                $text = e($config['text'] ?? '');
                $text = preg_replace_callback('/\{\{(\w+)\}\}/', function ($m) use ($diploma) {
                    return $this->resolveVariable($m[1], $diploma);
                }, $text);
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};
  font-size:{$fontSize}{$unit};color:{$color};text-align:{$align};font-weight:{$bold};font-style:{$italic};">
  {$text}
</div>
HTML;

            case 'variable':
                $value = $this->resolveVariable($el->variable, $diploma);
                $value = preg_replace_callback('/\{\{(\w+)\}\}/', function ($m) use ($diploma) {
                    return $this->resolveVariable($m[1], $diploma);
                }, $value);
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};
  font-size:{$fontSize}{$unit};color:{$color};text-align:{$align};font-weight:{$bold};font-style:{$italic};">
  {$value}
</div>
HTML;

            case 'qr':
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};">
  <img src="{$qrBase64}" style="width:100%;height:100%;object-fit:contain;">
</div>
HTML;

            case 'rect':
                $fill = $config['fill'] ?? 'transparent';
                $stroke = $config['stroke'] ?? '#000000';
                $strokeW = round(($config['strokeWidth'] ?? 1) * $scaleX, 4);
                $rx = round(($config['rx'] ?? 0) * $scaleX, 4);
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};
  background:{$fill};border:{$strokeW}{$unit} solid {$stroke};border-radius:{$rx}{$unit};">
</div>
HTML;

            case 'line':
                $stroke = $config['stroke'] ?? '#000000';
                $strokeW = round(($config['strokeWidth'] ?? 2) * $scaleX, 4);
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};">
  <svg width="{$width}{$unit}" height="{$height}{$unit}" style="overflow:visible;">
    <line x1="0" y1="0" x2="{$width}{$unit}" y2="{$height}{$unit}"
      stroke="{$stroke}" stroke-width="{$strokeW}{$unit}" />
  </svg>
</div>
HTML;

            case 'image':
                $src = $config['src'] ?? '';
                if (!$src) return '';
                return <<<HTML
<div class="el" style="left:{$left}{$unit};top:{$top}{$unit};width:{$width}{$unit};height:{$height}{$unit};">
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
