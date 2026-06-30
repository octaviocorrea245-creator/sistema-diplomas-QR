<?php

namespace App\Services;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QrGenerator
{
    public function generate(string $data, string $outputPath): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'eccLevel'        => EccLevel::M,
            'scale'           => 12,
            'outputBase64'    => false,
        ]);

        $qrcode = new QRCode($options);
        $pngData = $qrcode->render($data);

        Storage::disk('public')->put($outputPath, $pngData);

        return Storage::url($outputPath);
    }

    public function generateBase64(string $data): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'eccLevel'        => EccLevel::M,
            'scale'           => 12,
            'outputBase64'    => true,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }
}
