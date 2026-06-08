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
        'emitido_por',
        'folio',
        'token_qr',
        'ruta_pdf',
        'fecha_emision',
        'estado'
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
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

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emitido_por');
    }

    public function reimpresiones()
    {
        return $this->hasMany(Reimpresion::class, 'diploma_id');
    }
}
