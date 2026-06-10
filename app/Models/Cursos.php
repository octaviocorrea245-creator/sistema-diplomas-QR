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

public function alumnos($id)
{
    $curso = Cursos::with(['departamento', 'users'])->findOrFail($id);
    return view('supervisor.cursos.alumnos', compact('curso'));
}
}