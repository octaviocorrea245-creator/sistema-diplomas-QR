<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $table = 'plantillas';

    protected $fillable = [
        'department_id',
        'nombre',
        'descripcion',
        'tipo',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'department_id');
    }

    public function versiones()
    {
        return $this->hasMany(VersionPlantilla::class, 'plantilla_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(Cursos::class, 'plantilla_curso', 'plantilla_id', 'curso_id')->withTimestamps();
    }
}
