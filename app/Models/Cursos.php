<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'estado'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'curso_usuario', 'curso_id', 'user_id')
                    ->withPivot('estado', 'fecha_completado')
                    ->withTimestamps();
    }
}