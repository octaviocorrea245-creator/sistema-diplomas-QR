<?php

namespace App\Console\Commands;

use App\Models\Firmante;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FirmaTestSetup extends Command
{
    protected $signature = 'firma:test-setup
                            {--dept=1       : ID del departamento donde crear el firmante}
                            {--pass=test123 : Contraseña para cifrar la llave privada}
                            {--force        : Sobreescribe si ya existe el firmante de prueba}';

    protected $description = 'Crea un certificado de prueba y un Firmante de test para simular e.firma SAT';

    public function handle(): int
    {
        $deptId   = (int) $this->option('dept');
        $password = $this->option('pass');

        // ── Verificar que la extensión openssl esté disponible ────────────
        if (!extension_loaded('openssl')) {
            $this->error('La extensión PHP openssl no está disponible.');
            return 1;
        }

        // ── Verificar que el departamento exista ──────────────────────────
        $dept = \App\Models\Departamento::find($deptId);
        if (!$dept) {
            $this->error("Departamento ID={$deptId} no encontrado. Usa --dept=<id>.");
            $this->line('Departamentos disponibles:');
            \App\Models\Departamento::all(['id','name'])->each(fn($d) =>
                $this->line("  [{$d->id}] {$d->name}")
            );
            return 1;
        }

        // ── ¿Ya existe firmante de prueba? ────────────────────────────────
        $existing = Firmante::where('departamento_id', $deptId)
            ->where('nombre', 'LIKE', '%Prueba%')
            ->first();

        if ($existing && !$this->option('force')) {
            $this->warn("Ya existe un firmante de prueba en este departamento (ID={$existing->id}).");
            $this->line("Usa --force para recrearlo o usa directamente la contraseña: <comment>{$password}</comment>");
            return 0;
        }

        $this->info('Generando certificado de prueba...');

        // ── 0. Fix XAMPP/Windows: OpenSSL necesita su archivo de config ───
        $opensslConf = $this->findOpensslConf();
        if ($opensslConf) {
            putenv('OPENSSL_CONF=' . $opensslConf);
            $this->line("  ✓ OpenSSL config: {$opensslConf}");
        }

        $sslConfig = $opensslConf ? ['config' => $opensslConf] : [];

        // ── 1. Generar par de claves RSA ──────────────────────────────────
        $privateKey = openssl_pkey_new(array_merge([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'digest_alg'       => 'sha256',
        ], $sslConfig));

        if (!$privateKey) {
            $this->error('No se pudo generar la llave privada: ' . openssl_error_string());
            $this->line('');
            $this->warn('Si usas XAMPP en Windows, verifica que exista:');
            $this->line('  C:\\xampp\\apache\\conf\\openssl.cnf');
            return 1;
        }

        // ── 2. Generar certificado autofirmado ────────────────────────────
        $dn = [
            'CN'           => 'FIRMANTE DE PRUEBA UPGP',
            'O'            => $dept->name ?? 'UPGP',
            'C'            => 'MX',
            'serialNumber' => 'UPGP000000XXXXXX',
        ];

        $csrConfig  = array_merge(['digest_alg' => 'sha256'], $sslConfig);
        $csr  = openssl_csr_new($dn, $privateKey, $csrConfig);
        $cert = openssl_csr_sign($csr, null, $privateKey, 365, $csrConfig);

        if (!$cert) {
            $this->error('No se pudo generar el certificado: ' . openssl_error_string());
            return 1;
        }

        // ── 3. Exportar llave privada cifrada (PEM con contraseña) ────────
        openssl_pkey_export($privateKey, $keyPem, $password, $sslConfig);

        // ── 4. Exportar certificado como DER (igual que .cer del SAT) ─────
        openssl_x509_export($cert, $certPem);
        // Convertir PEM → DER: quitar cabecera/pie y decodificar base64
        $certBase64 = preg_replace('/-----[^-]+-----|\s/', '', $certPem);
        $certDer    = base64_decode($certBase64);

        // ── 5. Parsear datos del certificado ──────────────────────────────
        $certInfo = openssl_x509_parse($cert);
        $serie    = isset($certInfo['serialNumber'])
            ? $this->hexToDecimal(dechex($certInfo['serialNumber']))
            : '00000000000000000001';
        $validTo  = isset($certInfo['validTo_time_t'])
            ? date('Y-m-d H:i:s', $certInfo['validTo_time_t'])
            : now()->addYear()->toDateTimeString();
        $validFrom = isset($certInfo['validFrom_time_t'])
            ? date('Y-m-d H:i:s', $certInfo['validFrom_time_t'])
            : now()->toDateTimeString();

        // ── 6. Guardar archivos en storage/app/private/ ───────────────────
        Storage::disk('local')->makeDirectory('private');
        $keyPath = 'private/test_firma_dept' . $deptId . '.key';
        $cerPath = 'private/test_firma_dept' . $deptId . '.cer';

        Storage::disk('local')->put($keyPath, $keyPem);
        Storage::disk('local')->put($cerPath, $certDer);

        $this->line("  ✓ Llave guardada en storage/app/{$keyPath}");
        $this->line("  ✓ Certificado guardado en storage/app/{$cerPath}");

        // ── 7. Crear/actualizar Firmante en la base de datos ──────────────
        if ($existing) {
            $existing->delete();
        }

        $firmante = Firmante::create([
            'nombre'             => 'Firmante de Prueba (TEST)',
            'cargo'              => 'Director de Prueba',
            'departamento_id'    => $deptId,
            'cer_path'           => $cerPath,
            'key_path'           => $keyPath,
            'certificado_numero' => $serie,
            'rfc'                => 'UPGP000000XXXXXX',
            'cert_valido_desde'  => $validFrom,
            'cert_expira_en'     => $validTo,
            'activo'             => true,
        ]);

        // ── 8. Resumen ────────────────────────────────────────────────────
        $this->newLine();
        $this->info('✅  Firmante de prueba creado correctamente.');
        $this->newLine();
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID Firmante',   $firmante->id],
                ['Nombre',        $firmante->nombre],
                ['Departamento',  $dept->name],
                ['RFC',           $firmante->rfc],
                ['Serie cert',    $firmante->certificado_numero],
                ['Expira',        $validTo],
                ['Contraseña',    $password],
            ]
        );

        $this->newLine();
        $this->line('<comment>Ahora puedes usar este firmante en la generación masiva de diplomas.</comment>');
        $this->line('<comment>Contraseña para la llave privada: <info>' . $password . '</info></comment>');
        $this->newLine();

        return 0;
    }

    /**
     * Busca el archivo openssl.cnf en ubicaciones comunes de XAMPP/Windows/Linux.
     */
    private function findOpensslConf(): ?string
    {
        $candidates = [
            // XAMPP Windows
            'C:\\xampp\\apache\\conf\\openssl.cnf',
            'C:\\xampp\\php\\extras\\openssl\\openssl.cnf',
            'C:\\xampp\\php\\openssl.cnf',
            // WAMP
            'C:\\wamp64\\bin\\apache\\apache2.4.54\\conf\\openssl.cnf',
            // PHP instalado directamente en Windows
            'C:\\PHP\\extras\\openssl\\openssl.cnf',
            // Variable de entorno ya definida
            getenv('OPENSSL_CONF') ?: '',
            // Linux / macOS (suele funcionar sin config, pero por si acaso)
            '/etc/ssl/openssl.cnf',
            '/usr/local/etc/openssl/openssl.cnf',
            '/usr/local/etc/openssl@3/openssl.cnf',
        ];

        foreach ($candidates as $path) {
            if ($path && file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Convierte un número hexadecimal a decimal usando BCMath.
     */
    private function hexToDecimal(string $hex): string
    {
        $hex = ltrim(strtolower($hex), '0x');
        $dec = '0';
        foreach (str_split($hex) as $ch) {
            $digit = strpos('0123456789abcdef', $ch);
            $dec   = bcadd(bcmul($dec, '16'), (string) $digit);
        }
        return $dec ?: '0';
    }
}
