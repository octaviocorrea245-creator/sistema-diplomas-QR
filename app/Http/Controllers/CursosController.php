<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use Illuminate\Http\Request;

class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $departamento_id = auth()->user()->department_id;

        $cursos = Cursos::where('departamento_id', $departamento_id)
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        // No necesita cargar departamentos, el admin ya tiene el suyo
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'horas'        => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2100-01-01',
            'fecha_fin'    => 'nullable|date_format:Y-m-d|after_or_equal:fecha_inicio|before:2100-01-01',
            'estado'       => 'required|in:borrador,activo,finalizado,cancelado',
        ]);

        Cursos::create([
            'departamento_id' => auth()->user()->department_id, // Se asigna automáticamente
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'horas'           => $request->horas,
            'fecha_inicio'    => $request->fecha_inicio,
            'fecha_fin'       => $request->fecha_fin,
            'estado'          => $request->estado,
        ]);

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso creado correctamente.');
    }

    public function edit($id)
    {
        $curso = Cursos::findOrFail($id);

        if ($curso->departamento_id != auth()->user()->department_id) {
            abort(403);
        }

        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, $id)
    {
        $curso = Cursos::findOrFail($id);

        if ($curso->departamento_id != auth()->user()->department_id) {
            abort(403);
        }

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'horas'        => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2100-01-01',
            'fecha_fin'    => 'nullable|date_format:Y-m-d|after_or_equal:fecha_inicio|before:2100-01-01',
            'estado'       => 'required|in:borrador,activo,finalizado,cancelado',
        ]);

        $curso->update($request->only([
            'nombre', 'descripcion', 'horas',
            'fecha_inicio', 'fecha_fin', 'estado',
        ]));

        return redirect()->route('admin.cursos.index')
                        ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $curso = Cursos::findOrFail($id);

        if ($curso->departamento_id != auth()->user()->department_id) {
            abort(403);
        }

        $curso->delete();

        return back()->with('success', 'Curso eliminado.');
    }
    public function show($id)
    {
        $cursos = Cursos::findOrFail($id);

        if ($cursos->departamento_id != auth()->user()->department_id) {
            abort(403);
        }

        return view('admin.cursos.show', compact('cursos'));
    }
}