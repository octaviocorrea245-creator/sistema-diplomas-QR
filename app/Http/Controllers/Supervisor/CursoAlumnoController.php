<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\User;
use Illuminate\Http\Request;

class CursoAlumnoController extends Controller
{
    // Lista de alumnos en un curso (supervisor ve todos)
    public function index(Cursos $curso)
    {
        $alumnos = $curso->alumnos()
            ->with('department')
            ->orderBy('full_name')
            ->paginate(20);

        return view('supervisor.cursos.alumnos.index', compact('curso', 'alumnos'));
    }

    // Detalle de un alumno en el contexto de un curso
    public function show(Cursos $curso, User $alumno)
    {
        $inscripcion = $curso->alumnos()->where('user_id', $alumno->id)->firstOrFail();
        $alumno->load('department');

        return view('supervisor.cursos.alumnos.show', compact('curso', 'alumno', 'inscripcion'));
    }
}
