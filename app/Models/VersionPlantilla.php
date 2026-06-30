<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionPlantilla extends Model
{
    use HasFactory;

    protected $table = 'versiones_plantilla';

    protected $fillable = [
        'plantilla_id',
        'version',
        'ruta_pdf_base',
        'fabric_json',
        'ruta_preview',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'version' => 'integer',
    ];

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'plantilla_id');
    }

    public function diplomas()
    {
        return $this->hasMany(Diploma::class, 'version_plantilla_id');
    }
}
