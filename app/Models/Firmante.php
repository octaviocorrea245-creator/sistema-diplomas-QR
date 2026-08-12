<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmante extends Model
{
    use HasFactory;

    protected $table = 'firmantes';

    protected $fillable = [
        'departamento_id',
        'nombre',
        'cargo',
        'cer_path',
        'key_path',
        'rfc',
        'certificado_numero',
        'cert_valido_desde',
        'cert_expira_en',
        'activo',
    ];

    protected $casts = [
        'cert_valido_desde' => 'datetime',
        'cert_expira_en'    => 'datetime',
        'activo'            => 'boolean',
    ];

    // ─── relaciones ───────────────────────────────────────────────────────────

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function diplomas()
    {
        return $this->hasMany(Diploma::class, 'firmante_id');
    }

    // ─── helpers ──────────────────────────────────────────────────────────────

    /**
     * Indica si el certificado está vigente en este momento.
     */
    public function certVigente(): bool
    {
        $now = now();

        if ($this->cert_valido_desde && $now->lt($this->cert_valido_desde)) {
            return false;
        }

        if ($this->cert_expira_en && $now->gt($this->cert_expira_en)) {
            return false;
        }

        return true;
    }

    /**
     * Días que faltan para que expire el certificado.
     * Devuelve null si no tiene fecha de expiración cargada.
     */
    public function diasParaExpirar(): ?int
    {
        if (!$this->cert_expira_en) {
            return null;
        }

        return (int) now()->diffInDays($this->cert_expira_en, false);
    }

    /**
     * Scope: solo firmantes activos con certificado vigente.
     */
    public function scopeDisponibles($query)
    {
        return $query->where('activo', true)
                     ->where(function ($q) {
                         $q->whereNull('cert_expira_en')
                           ->orWhere('cert_expira_en', '>', now());
                     });
    }
}
