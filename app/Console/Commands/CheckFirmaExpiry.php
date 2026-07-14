<?php

namespace App\Console\Commands;

use App\Models\Firmante;
use Illuminate\Console\Command;

class CheckFirmaExpiry extends Command
{
    protected $signature = 'firma:check-expiry
                            {--dias=30 : Días de anticipación para considerar "próximo a expirar"}
                            {--solo-criticos : Mostrar solo expirados y los que expiran en ≤7 días}';

    protected $description = 'Lista firmantes con certificados expirados o próximos a expirar';

    public function handle(): int
    {
        $dias       = (int) $this->option('dias');
        $soloCritic = $this->option('solo-criticos');

        $firmantes = Firmante::where('activo', true)
            ->whereNotNull('cert_expira_en')
            ->orderBy('cert_expira_en')
            ->get();

        $expirados  = collect();
        $proximos   = collect();

        foreach ($firmantes as $f) {
            $diasRestantes = $f->diasParaExpirar();

            if ($diasRestantes === null) {
                continue;
            }

            if ($diasRestantes < 0) {
                $expirados->push(['firmante' => $f, 'dias' => $diasRestantes]);
            } elseif ($diasRestantes <= $dias) {
                if (!$soloCritic || $diasRestantes <= 7) {
                    $proximos->push(['firmante' => $f, 'dias' => $diasRestantes]);
                }
            }
        }

        if ($expirados->isEmpty() && $proximos->isEmpty()) {
            $this->info("✔  Todos los certificados están vigentes (umbral: {$dias} días).");
            return self::SUCCESS;
        }

        if ($expirados->isNotEmpty()) {
            $this->newLine();
            $this->error('  CERTIFICADOS EXPIRADOS  ');
            $rows = $expirados->map(fn($e) => [
                $e['firmante']->nombre,
                $e['firmante']->rfc ?? '—',
                $e['firmante']->cert_expira_en->format('d/m/Y'),
                abs($e['dias']) . ' días',
            ])->toArray();

            $this->table(['Nombre', 'RFC', 'Expiró el', 'Hace'], $rows);
        }

        if ($proximos->isNotEmpty()) {
            $this->newLine();
            $this->warn('  PRÓXIMOS A EXPIRAR  ');
            $rows = $proximos->map(fn($p) => [
                $p['firmante']->nombre,
                $p['firmante']->rfc ?? '—',
                $p['firmante']->cert_expira_en->format('d/m/Y'),
                $p['dias'] . ' días',
            ])->toArray();

            $this->table(['Nombre', 'RFC', 'Expira el', 'Faltan'], $rows);
        }

        $total = $expirados->count() + $proximos->count();
        $this->newLine();
        $this->line("  Total de alertas: <fg=yellow>{$total}</>");

        // Devuelve código de error para que CI/CD pueda detectarlo
        return $expirados->isNotEmpty() ? self::FAILURE : self::SUCCESS;
    }
}
