<?php

namespace App\Services;

use App\Models\Firmante;
use RuntimeException;

/**
 * Maneja la carga y validación de certificados e.firma SAT
 * y prepara los parámetros necesarios para firmar PDFs con TCPDF.
 */
class PdfSigner
{
    /**
     * Lee el .cer (formato DER) y lo convierte a PEM.
     */
    public function cerToPem(string $cerPath): string
    {
        if (!file_exists($cerPath)) {
            throw new RuntimeException("Archivo .cer no encontrado: {$cerPath}");
        }

        $der = file_get_contents($cerPath);
        if ($der === false || strlen($der) < 10) {
            throw new RuntimeException('No se pudo leer el archivo .cer');
        }

        return "-----BEGIN CERTIFICATE-----\n"
             . chunk_split(base64_encode($der), 64, "\n")
             . "-----END CERTIFICATE-----\n";
    }

    /**
     * Extrae los datos del certificado .cer (RFC, nombre, vigencia, serie).
     *
     * @return array{rfc:string|null, nombre:string|null, certificado_numero:string|null, valido_desde:string|null, expira_en:string|null}
     */
    public function parseCertificate(string $cerPath): array
    {
        $pem  = $this->cerToPem($cerPath);
        $cert = openssl_x509_read($pem);

        if (!$cert) {
            throw new RuntimeException('OpenSSL no pudo leer el certificado: ' . openssl_error_string());
        }

        $info = openssl_x509_parse($cert);

        // El RFC del SAT va en el campo x500UniqueIdentifier del Subject
        $rfc = $info['subject']['x500UniqueIdentifier']
            ?? $info['subject']['serialNumber']
            ?? null;

        // Número de serie en hexadecimal → decimal (como lo muestra el SAT)
        $serie = isset($info['serialNumber'])
            ? $this->hexSerialToDecimal($info['serialNumber'])
            : null;

        return [
            'rfc'                => $rfc,
            'nombre'             => $info['subject']['CN'] ?? null,
            'certificado_numero' => $serie,
            'valido_desde'       => isset($info['validFrom_time_t'])
                ? date('Y-m-d H:i:s', $info['validFrom_time_t'])
                : null,
            'expira_en'          => isset($info['validTo_time_t'])
                ? date('Y-m-d H:i:s', $info['validTo_time_t'])
                : null,
        ];
    }

    /**
     * Verifica que el par .cer / .key coincidan y que la contraseña sea correcta.
     * Devuelve la llave privada como recurso OpenSSL si todo es correcto.
     *
     * @throws RuntimeException si la contraseña es incorrecta o los archivos no coinciden
     */
    public function unlockPrivateKey(string $keyPath, string $password): \OpenSSLAsymmetricKey
    {
        if (!file_exists($keyPath)) {
            throw new RuntimeException("Archivo .key no encontrado: {$keyPath}");
        }

        $keyData    = file_get_contents($keyPath);
        $privateKey = openssl_pkey_get_private($keyData, $password);

        if (!$privateKey) {
            throw new RuntimeException(
                'No se pudo descifrar la llave privada. Verifica la contraseña. '
                . openssl_error_string()
            );
        }

        return $privateKey;
    }

    /**
     * Valida que el certificado aún esté vigente.
     *
     * @throws RuntimeException si el certificado está vencido o aún no es válido
     */
    public function validateExpiry(Firmante $firmante): void
    {
        $now = now();

        if ($firmante->cert_valido_desde && $now->lt($firmante->cert_valido_desde)) {
            throw new RuntimeException(
                "El certificado aún no es válido. Válido desde: {$firmante->cert_valido_desde}"
            );
        }

        if ($firmante->cert_expira_en && $now->gt($firmante->cert_expira_en)) {
            throw new RuntimeException(
                "El certificado expiró el {$firmante->cert_expira_en}. Renueva la e.firma."
            );
        }
    }

    /**
     * Prepara los parámetros de firma para pasarlos a PdfGenerator::generate().
     * Lanza excepción si la contraseña es incorrecta o el certificado está vencido.
     *
     * @return array{cert_pem:string, key_resource:\OpenSSLAsymmetricKey, serie:string|null}
     */
    public function prepareSignatureParams(Firmante $firmante, string $password): array
    {
        $this->validateExpiry($firmante);

        $cerPath = storage_path('app/private/' . $firmante->cer_path);
        $keyPath = storage_path('app/private/' . $firmante->key_path);

        $certPem     = $this->cerToPem($cerPath);
        $keyResource = $this->unlockPrivateKey($keyPath, $password);

        return [
            'cert_pem'     => $certPem,
            'key_resource' => $keyResource,
            'serie'        => $firmante->certificado_numero,
        ];
    }

    // ─── helpers ──────────────────────────────────────────────────────────────

    /**
     * El número de serie del SAT viene en hex; lo convierte al decimal de 20 dígitos.
     */
    private function hexSerialToDecimal(string $hex): string
    {
        // A veces llega con prefijo "0x" o como decimal directamente
        if (str_starts_with($hex, '0x') || str_starts_with($hex, '0X')) {
            $hex = substr($hex, 2);
        }

        // Si es numérico puro, ya es decimal
        if (ctype_digit($hex)) {
            return $hex;
        }

        // Conversión hex → decimal usando BCMath (siempre disponible en PHP)
        $dec = '0';
        $hex = strtolower($hex);
        $len = strlen($hex);
        for ($i = 0; $i < $len; $i++) {
            $digit = strpos('0123456789abcdef', $hex[$i]);
            $dec   = bcadd(bcmul($dec, '16'), (string) $digit);
        }

        return $dec;
    }
}
