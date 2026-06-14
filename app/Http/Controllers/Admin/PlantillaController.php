<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use App\Models\Cursos;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    private function departamentoId()
    {
        return auth()->user()->department_id;
    }

    public function index()
    {
        $plantillas = Plantilla::where('department_id', $this->departamentoId())
            ->with('versiones')
            ->orderBy('nombre')
            ->get();

        return view('admin.plantillas.index', compact('plantillas'));
    }

    public function create()
    {
        $cursos = Cursos::where('departamento_id', $this->departamentoId())
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.plantillas.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string|max:1000',
            'tipo'         => 'required|in:global,grupo,individual',
            'cursos'       => 'nullable|array',
            'cursos.*'     => 'exists:cursos,id',
        ]);

        $plantilla = Plantilla::create([
            'department_id' => $this->departamentoId(),
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'tipo'          => $request->tipo,
            'activa'        => true,
        ]);

        if ($request->filled('cursos')) {
            $plantilla->cursos()->sync($request->cursos);
        }

        return redirect()->route('admin.plantillas.show', $plantilla)
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(Plantilla $plantilla)
    {
        abort_unless($plantilla->department_id === $this->departamentoId(), 403);

        $cursos = Cursos::where('departamento_id', $this->departamentoId())
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.plantillas.edit', compact('plantilla', 'cursos'));
    }

    public function update(Request $request, Plantilla $plantilla)
    {
        abort_unless($plantilla->department_id === $this->departamentoId(), 403);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string|max:1000',
            'tipo'         => 'required|in:global,grupo,individual',
            'cursos'       => 'nullable|array',
            'cursos.*'     => 'exists:cursos,id',
            'activa'       => 'boolean',
        ]);

        $plantilla->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo'        => $request->tipo,
            'activa'      => $request->boolean('activa'),
        ]);

        if ($request->has('cursos')) {
            $plantilla->cursos()->sync($request->cursos ?? []);
        }

        return redirect()->route('admin.plantillas.show', $plantilla)
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function show(Plantilla $plantilla)
    {
        abort_unless($plantilla->department_id === $this->departamentoId(), 403);

        $plantilla->load(['versiones', 'cursos', 'departamento']);

        return view('admin.plantillas.show', compact('plantilla'));
    }

    public function destroy(Plantilla $plantilla)
    {
        abort_unless($plantilla->department_id === $this->departamentoId(), 403);

        $plantilla->delete();

        return redirect()->route('admin.plantillas.index')
            ->with('success', 'Plantilla eliminada correctamente.');
    }
}
