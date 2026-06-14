<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cursos extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'departamento_id',
        'nombre',
        'descripcion',
        'horas',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'curso_usuario', 'curso_id', 'user_id')
                    ->withPivot('estado')
                    ->withTimestamps();
    }

    // Relacion usada por el modulo de alumnos (incluye fecha_completado en el pivot)
    public function alumnos()
    {
        return $this->belongsToMany(User::class, 'curso_usuario', 'curso_id', 'user_id')
                    ->withPivot('estado', 'fecha_completado')
                    ->withTimestamps();
    }

    public function template()
    {
        return $this->hasOne(DiplomaTemplate::class, 'curso_id');
    }

    public function scopeDelDepartamento($query, int $departmentId)
    {
        return $query->where('departamento_id', $departmentId);
    }
}