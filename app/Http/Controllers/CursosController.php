<?php

namespace App\Http\Controllers;

use App\Models\Cursos;
use App\Models\Departamento;
use Illuminate\Http\Request;

class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cursos = Cursos::with('departamento')->orderBy('nombre')->get();

        if ($request->routeIs('supervisor.*')) {
            return view('supervisor.cursos.index', compact('cursos'));
        }

        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::orderBy('name')->get();
        return view('admin.cursos.create', compact('departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'horas'           => 'nullable|integer|min:1',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'          => 'required|in:borrador,activo,finalizado,cancelado',
        ]);

        Cursos::create($data);

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Cursos $curso)
    {
        $curso->load('departamento', 'users');

        if ($request->routeIs('supervisor.*')) {
            return view('supervisor.cursos.show', compact('curso'));
        }

        return view('admin.cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cursos $curso)
    {
        $departamentos = Departamento::orderBy('name')->get();
        return view('admin.cursos.edit', compact('curso', 'departamentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cursos $curso)
    {
        $data = $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'horas'           => 'nullable|integer|min:1',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'          => 'required|in:borrador,activo,finalizado,cancelado',
        ]);

        $curso->update($data);

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cursos $curso)
    {
        $curso->delete();

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso eliminado correctamente.');
    }

    /**
     * List students registered in the course.
     */
    public function alumnos(Request $request, Cursos $curso)
    {
        $curso->load('users');
        $alumnos = $curso->users;

        return view('supervisor.cursos.alumnos.index', compact('curso', 'alumnos'));
    }
}
