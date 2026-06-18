<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\CursoUsuario;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CursoAlumnoController extends Controller
{
    // Verifica que el curso pertenece al departamento del admin autenticado
    private function verificarCurso(Cursos $curso): void
    {
        abort_unless(auth()->user()->department_id === $curso->departamento_id, 403);
    }

    // Lista de alumnos inscritos en un curso
    public function index(Request $request, Cursos $curso)
    {
        $this->verificarCurso($curso);

        $query = $curso->alumnos();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->wherePivot('estado', $request->estado);
        }

        $alumnos = $query->orderBy('full_name')->paginate(15)->withQueryString();

        return view('admin.cursos.alumnos.index', compact('curso', 'alumnos'));
    }

    // Detalle de un alumno dentro del contexto del curso
    public function show(Cursos $curso, User $alumno)
    {
        $this->verificarCurso($curso);

        // Verificar que el alumno esta inscrito en este curso
        $inscripcion = $curso->alumnos()->where('user_id', $alumno->id)->firstOrFail();

        return view('admin.cursos.alumnos.show', compact('curso', 'alumno', 'inscripcion'));
    }

    // Formulario para agregar/crear alumno en el curso
    public function create(Cursos $curso)
    {
        $this->verificarCurso($curso);

        // Alumnos del mismo departamento que aun no estan inscritos en este curso
        $alumnosDisponibles = User::alumnos()
            ->where('department_id', $curso->departamento_id)
            ->whereNotIn('id', $curso->alumnos()->pluck('users.id'))
            ->orderBy('full_name')
            ->get();

        return view('admin.cursos.alumnos.create', compact('curso', 'alumnosDisponibles'));
    }

    // Guarda un alumno nuevo o lo inscribe si ya existe
    public function store(Request $request, Cursos $curso)
    {
        $this->verificarCurso($curso);

        $request->validate([
            'modo'            => ['required', Rule::in(['nuevo', 'existente'])],
            // Campos para alumno nuevo
            'full_name'       => ['required_if:modo,nuevo', 'nullable', 'string', 'max:255'],
            'username'        => ['required_if:modo,nuevo', 'nullable', 'string', 'unique:users,username'],
            'password'        => ['required_if:modo,nuevo', 'nullable', 'string', 'min:8'],
            // Campo para alumno existente
            'user_id'         => ['required_if:modo,existente', 'nullable', 'exists:users,id'],
            // Datos de inscripcion
            'estado'          => ['required', Rule::in(CursoUsuario::estados())],
            'fecha_completado' => ['nullable', 'date', 'required_if:estado,completado'],
        ]);

        if ($request->modo === 'nuevo') {
            // Crear el usuario alumno y asignarle el rol
            $alumno = User::create([
                'full_name'     => $request->full_name,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'role'          => 'beneficiario',
                'department_id' => $curso->departamento_id,
            ]);
            $alumno->assignRole('Beneficiario');
        } else {
            $alumno = User::findOrFail($request->user_id);

            // No reinscribir si ya esta en el curso
            if ($curso->alumnos()->where('user_id', $alumno->id)->exists()) {
                return back()->withErrors(['user_id' => 'Este alumno ya está inscrito en el curso.']);
            }
        }

        $curso->alumnos()->attach($alumno->id, [
            'estado'           => $request->estado,
            'fecha_completado' => $request->fecha_completado,
        ]);

        return redirect()
            ->route('admin.cursos.alumnos.index', $curso)
            ->with('success', "Alumno {$alumno->display_name} inscrito correctamente.");
    }

    // Formulario para editar el estado de un alumno en el curso
    public function edit(Cursos $curso, User $alumno)
    {
        $this->verificarCurso($curso);

        $inscripcion = $curso->alumnos()->where('user_id', $alumno->id)->firstOrFail();

        return view('admin.cursos.alumnos.edit', compact('curso', 'alumno', 'inscripcion'));
    }

    // Actualiza el estado de inscripcion del alumno
    public function update(Request $request, Cursos $curso, User $alumno)
    {
        $this->verificarCurso($curso);

        $request->validate([
            'estado'           => ['required', Rule::in(CursoUsuario::estados())],
            'fecha_completado' => ['nullable', 'date', 'required_if:estado,completado'],
        ]);

        $curso->alumnos()->updateExistingPivot($alumno->id, [
            'estado'           => $request->estado,
            'fecha_completado' => $request->fecha_completado,
        ]);

        return redirect()
            ->route('admin.cursos.alumnos.show', [$curso, $alumno])
            ->with('success', 'Inscripción actualizada.');
    }

    // Dar de baja al alumno del curso (no elimina, cambia estado)
    public function destroy(Cursos $curso, User $alumno)
    {
        $this->verificarCurso($curso);

        $curso->alumnos()->updateExistingPivot($alumno->id, [
            'estado' => CursoUsuario::ESTADO_BAJA,
        ]);

        return redirect()
            ->route('admin.cursos.alumnos.index', $curso)
            ->with('success', 'Alumno dado de baja del curso.');
    }
}
