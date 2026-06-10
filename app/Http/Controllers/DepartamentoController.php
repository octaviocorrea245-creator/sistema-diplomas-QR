<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    // Mostrar la lista de departamentos
    public function index()
    {
        // Equivale a: SELECT * FROM departments ORDER BY name ASC
        $departments = Departamento::orderBy('name', 'asc')->get();
        
        return view('supervisor.departamentos.index', compact('departments'));
    }

    // Mostrar el formulario de creación
    public function create()
    {
        return view('supervisor.departamentos.create');
    }

    // Guardar un nuevo departamento
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Departamento::create([
            'name' => $request->name
        ]);

        return redirect()->route('supervisor.departamentos.index')
                         ->with('success', 'Departamento añadido correctamente.');
    }

    // Mostrar el formulario de edición
    public function edit(Departamento $departamento)
    {
        // Usamos Route Model Binding para obtener el modelo automáticamente desde la URL
        return view('supervisor.departamentos.edit', compact('departamento'));
    }

    // Actualizar el departamento
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $departamento->update([
            'name' => $request->name
        ]);

        return redirect()->route('supervisor.departamentos.index')
                         ->with('success', 'Departamento actualizado correctamente.');
    }

    // Eliminar el departamento
    public function destroy(Departamento $departamento)
    {
        // Si tienes la eliminación en cascada en la migración de la BD,
        // al borrar el departamento se limpiarán los usuarios asociados automáticamente.
        $departamento->delete();

        return redirect()->route('supervisor.departamentos.index')
                         ->with('success', 'Departamento eliminado correctamente.');
    }
}