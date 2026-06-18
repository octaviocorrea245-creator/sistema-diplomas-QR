<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    // Lista de todos los alumnos con filtros por departamento
    public function index(Request $request)
    {
        $departamentos = Departamento::orderBy('name')->get();

        $alumnos = User::with(['department', 'cursos'])
            ->alumnos()
            ->when($request->departamento_id, fn($q) =>
                $q->where('department_id', $request->departamento_id)
            )
            ->when($request->buscar, fn($q) =>
                $q->where('full_name', 'like', "%{$request->buscar}%")
                  ->orWhere('username', 'like', "%{$request->buscar}%")
            )
            ->orderBy('full_name')
            ->paginate(20)
            ->withQueryString();

        return view('supervisor.alumnos.index', compact('alumnos', 'departamentos'));
    }

    // Detalle de un alumno: todos sus cursos inscritos
    public function show(User $alumno)
    {
        $alumno->load([
            'department',
            'cursos' => fn($q) => $q->with('department')->orderBy('fecha_inicio', 'desc'),
        ]);

        return view('supervisor.alumnos.show', compact('alumno'));
    }
}
