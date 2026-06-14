<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $alumnos = User::with('cursos')
            ->alumnos()
            ->delDepartamento($admin->department_id)
            ->when($request->buscar, fn($q) =>
                $q->where('full_name', 'like', "%{$request->buscar}%")
            )
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.alumnos.index', compact('alumnos'));
    }

    public function show(User $alumno)
    {
        $admin = auth()->user();
        abort_unless($alumno->department_id === $admin->department_id, 403);

        $alumno->load([
            'cursos' => fn($q) => $q->where('departamento_id', $admin->department_id)
                                    ->orderBy('fecha_inicio', 'desc'),
        ]);

        return view('admin.alumnos.show', compact('alumno'));
    }

    public function create()
    {
        return view('admin.alumnos.create');
    }

    public function store(Request $request)
    {
        $admin = auth()->user();

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
        ]);

        $alumno = User::create([
            'full_name'     => $data['full_name'],
            'role'          => 'beneficiario',
            'department_id' => $admin->department_id,
        ]);

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', "Alumno \"{$alumno->full_name}\" creado correctamente.");
    }

    public function edit(User $alumno)
    {
        $admin = auth()->user();
        abort_unless($alumno->department_id === $admin->department_id, 403);

        return view('admin.alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, User $alumno)
    {
        $admin = auth()->user();
        abort_unless($alumno->department_id === $admin->department_id, 403);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
        ]);

        $alumno->update($data);

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', "Alumno \"{$alumno->full_name}\" actualizado.");
    }

    public function destroy(User $alumno)
    {
        $admin = auth()->user();
        abort_unless($alumno->department_id === $admin->department_id, 403);

        $alumno->delete();

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', "Alumno \"{$alumno->full_name}\" eliminado.");
    }
}
