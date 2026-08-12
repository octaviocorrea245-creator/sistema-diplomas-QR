<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmaAuditoria extends Model
{
    // Solo created_at, sin updated_at
    public $timestamps   = false;
    const CREATED_AT     = 'created_at';

    protected $fillable = [
        'diploma_id',
        'firmante_id',
        'usuario_id',
        'accion',
        'cert_serie',
        'ip',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }

    public function firmante(): BelongsTo
    {
        return $this->belongsTo(Firmante::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }
}
