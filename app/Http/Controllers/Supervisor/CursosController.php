<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;

class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:supervisor']);
    }

    public function index()
    {
        $cursos = Cursos::with('departamento')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('supervisor.cursos.index', compact('cursos'));
    }

    public function show($id)
{
    $curso = Cursos::with(['departamento', 'users'])->findOrFail($id);
    return view('supervisor.cursos.show', compact('curso'));
}
}