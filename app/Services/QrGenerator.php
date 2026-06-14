<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QrGenerator
{
    public function generate(string $data, string $outputPath): string
    {
        $options = new QROptions([
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_M,
            'scale'        => 12,
            'imageBase64'  => false,
        ]);

        $qrcode = new QRCode($options);
        $pngData = $qrcode->render($data);

        Storage::disk('public')->put($outputPath, base64_decode($pngData));

        return Storage::url($outputPath);
    }

    public function generateBase64(string $data): string
    {
        $options = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_M,
            'scale'       => 12,
            'imageBase64' => true,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }
}
