<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    use HasFactory;

    protected $table = 'diplomas';

    protected $fillable = [
        'user_id',
        'curso_id',
        'version_plantilla_id',
        'template_id',
        'emitido_por',
        'folio',
        'token_qr',
        'ruta_pdf',
        'fecha_emision',
        'estado',
        // e-firma
        'firmante_id',
        'firmado_en',
        'tiene_firma_digital',
        'cert_serie_usada',
    ];

    protected $casts = [
        'fecha_emision'       => 'datetime',
        'firmado_en'          => 'datetime',
        'tiene_firma_digital' => 'boolean',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    public function versionPlantilla()
    {
        return $this->belongsTo(VersionPlantilla::class, 'version_plantilla_id');
    }

    public function template()
    {
        return $this->belongsTo(DiplomaTemplate::class, 'template_id');
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emitido_por');
    }

    public function reimpresiones()
    {
        return $this->hasMany(Reimpresion::class, 'diploma_id');
    }

    public function firmante()
    {
        return $this->belongsTo(Firmante::class, 'firmante_id');
    }

    // ─── helpers ──────────────────────────────────────────────────────────────

    public function firmaAuditorias()
    {
        return $this->hasMany(FirmaAuditoria::class);
    }

    // ─── helpers ──────────────────────────────────────────────────────────────

    public function estaFirmado(): bool
    {
        return $this->tiene_firma_digital && $this->firmante_id !== null;
    }
}
