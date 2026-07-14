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

    public function index(Request $request)
    {
        $departamento_id = auth()->user()->department_id;

        $query = Cursos::where('departamento_id', $departamento_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $cursos = $query->with('template')->orderBy('created_at', 'desc')->get();

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
        $cursos = Cursos::with('template.elements')->findOrFail($id);

        if ($cursos->departamento_id != auth()->user()->department_id) {
            abort(403);
        }

        $alumnosQuery = $cursos->alumnos();
        $stats = [
            'total'      => (clone $alumnosQuery)->count(),
            'inscrito'   => (clone $alumnosQuery)->wherePivot('estado', 'inscrito')->count(),
            'en_curso'   => (clone $alumnosQuery)->wherePivot('estado', 'en_curso')->count(),
            'completado' => (clone $alumnosQuery)->wherePivot('estado', 'completado')->count(),
            'baja'       => (clone $alumnosQuery)->wherePivot('estado', 'baja')->count(),
        ];

        return view('admin.cursos.show', compact('cursos', 'stats'));
    }
}