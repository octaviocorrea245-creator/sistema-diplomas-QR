<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Departamento;

class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:supervisor']);
    }

    public function index()
    {
        $query = Cursos::with('departamento');

        if ($search = request('search')) {
            $query->where('nombre', 'like', "%{$search}%");
        }
        if ($departamento_id = request('departamento_id')) {
            $query->where('departamento_id', $departamento_id);
        }
        if ($estado = request('estado')) {
            $query->where('estado', $estado);
        }

        $cursos = $query->orderBy('created_at', 'desc')->get();

        $departamentos = Departamento::orderBy('name')->get();

        return view('supervisor.cursos.index', compact('cursos', 'departamentos'));
    }

    public function show($id)
{
    $curso = Cursos::with(['departamento', 'users', 'template.elements'])->findOrFail($id);
    $stats = [
        'total'       => $curso->users->count(),
        'inscrito'    => $curso->users->filter(fn($u) => $u->pivot->estado === 'inscrito')->count(),
        'en_curso'    => $curso->users->filter(fn($u) => $u->pivot->estado === 'en_curso')->count(),
        'completado'  => $curso->users->filter(fn($u) => $u->pivot->estado === 'completado')->count(),
        'baja'        => $curso->users->filter(fn($u) => $u->pivot->estado === 'baja')->count(),
    ];
    return view('supervisor.cursos.show', compact('curso', 'stats'));
}
}