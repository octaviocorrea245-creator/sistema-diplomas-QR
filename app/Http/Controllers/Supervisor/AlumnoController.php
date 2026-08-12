<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $departamentos = Departamento::orderBy('name')->get();

        $cursos = Cursos::with('departamento')
            ->when($request->departamento_id, fn($q) =>
                $q->where('departamento_id', $request->departamento_id)
            )
            ->orderBy('nombre')
            ->get();

        $alumnos = User::with(['department', 'cursos.departamento'])
            ->alumnos()
            ->when($request->departamento_id, fn($q) =>
                $q->where('department_id', $request->departamento_id)
            )
            ->when($request->curso_id, fn($q) =>
                $q->whereHas('cursos', fn($cq) =>
                    $cq->where('curso_id', $request->curso_id)
                )
            )
            ->when($request->buscar, fn($q) =>
                $q->where(function($nq) use ($request) {
                    $nq->where('full_name', 'like', "%{$request->buscar}%")
                       ->orWhere('username', 'like', "%{$request->buscar}%");
                })
            )
            ->when($request->sort === 'za', fn($q) =>
                $q->orderBy('full_name', 'desc'),
                fn($q) => $q->orderBy('full_name')
            )
            ->paginate(20)
            ->withQueryString();

        return view('supervisor.alumnos.index', compact('alumnos', 'departamentos', 'cursos'));
    }

    public function show(User $alumno)
    {
        $alumno->load([
            'department',
            'cursos' => fn($q) => $q->with('departamento')->orderBy('fecha_inicio', 'desc'),
        ]);

        return view('supervisor.alumnos.show', compact('alumno'));
    }

    public function uploadAvatar(Request $request, User $alumno)
    {
        abort_unless($alumno->hasRole('beneficiario'), 403);

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($alumno->avatar) {
            Storage::disk('public')->delete('avatars/'.$alumno->avatar);
        }

        $filename = 'alumno_'.$alumno->id.'_'.time().'.'.$request->file('avatar')->extension();
        $request->file('avatar')->storeAs('avatars', $filename, 'public');

        $alumno->update(['avatar' => $filename]);

        return redirect()
            ->route('supervisor.alumnos.show', $alumno)
            ->with('toast', ['message' => 'Foto de perfil actualizada.', 'type' => 'success']);
    }
}
