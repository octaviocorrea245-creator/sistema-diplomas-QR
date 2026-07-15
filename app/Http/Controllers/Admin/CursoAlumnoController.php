<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\CursoUsuario;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        // Todos los cursos en los que está inscrito el alumno (del mismo departamento)
        $todosCursos = $alumno->cursos()
            ->where('departamento_id', $curso->departamento_id)
            ->withPivot('estado', 'fecha_completado')
            ->orderBy('nombre')
            ->get();

        // Cursos del departamento donde el alumno NO está inscrito (para inscribir en otro)
        $cursosDisponibles = Cursos::where('departamento_id', $curso->departamento_id)
            ->whereNotIn('id', $alumno->cursos()->pluck('cursos.id'))
            ->orderBy('nombre')
            ->get();

        return view('admin.cursos.alumnos.show', compact('curso', 'alumno', 'inscripcion', 'todosCursos', 'cursosDisponibles'));
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

    // Formulario de importación masiva CSV
    public function importForm(Cursos $curso)
    {
        $this->verificarCurso($curso);
        return view('admin.cursos.alumnos.import', compact('curso'));
    }

    // Procesa el CSV y crea/inscribe alumnos
    public function import(Request $request, Cursos $curso)
    {
        $this->verificarCurso($curso);

        $request->validate([
            'archivo' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
        ], [
            'archivo.required' => 'Selecciona un archivo CSV.',
            'archivo.mimes'    => 'El archivo debe ser .csv o .txt.',
            'archivo.max'      => 'El archivo no puede superar 4 MB.',
        ]);

        $handle = fopen($request->file('archivo')->getRealPath(), 'r');

        // Leer encabezado y normalizar (quitar BOM si existe)
        $rawHeader = fgetcsv($handle);
        $rawHeader[0] = ltrim($rawHeader[0], "\xEF\xBB\xBF"); // BOM UTF-8
        $header = array_map(fn($h) => strtolower(trim($h)), $rawHeader);

        $estadosValidos = CursoUsuario::estados();
        $res = ['creados' => [], 'inscritos' => [], 'actualizados' => [], 'errores' => []];
        $fila = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $fila++;
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }
            $data = array_combine($header, $row);

            $nombre   = trim($data['nombre_completo'] ?? $data['nombre'] ?? '');
            $username = trim($data['username'] ?? $data['usuario'] ?? '');
            $estado   = trim($data['estado'] ?? 'inscrito');
            $fecha    = trim($data['fecha_completado'] ?? '') ?: null;

            if ($nombre === '') {
                $res['errores'][] = "Fila {$fila}: nombre_completo es obligatorio.";
                continue;
            }

            if (!in_array($estado, $estadosValidos)) {
                $estado = 'inscrito';
            }

            // Buscar usuario: primero por username, luego por nombre exacto en el dpto
            $alumno = null;
            if ($username !== '') {
                $alumno = User::where('username', $username)->first();
            }
            if (!$alumno) {
                $alumno = User::where('full_name', $nombre)
                    ->where('department_id', $curso->departamento_id)
                    ->first();
            }

            $esNuevo = false;
            if (!$alumno) {
                // Generar username único si no viene en el CSV
                $baseUsername = $username ?: Str::slug($nombre, '');
                $baseUsername = $baseUsername ?: 'alumno';
                $uUsername    = $baseUsername;
                $suffix       = 1;
                while (User::where('username', $uUsername)->exists()) {
                    $uUsername = $baseUsername . $suffix++;
                }

                $alumno = User::create([
                    'full_name'     => $nombre,
                    'username'      => $uUsername,
                    'password'      => Hash::make('Cambiar@' . rand(1000, 9999)),
                    'role'          => 'beneficiario',
                    'department_id' => $curso->departamento_id,
                ]);
                $alumno->assignRole('Beneficiario');
                $esNuevo = true;
            }

            $pivotData = ['estado' => $estado, 'fecha_completado' => $fecha];

            if ($curso->alumnos()->where('user_id', $alumno->id)->exists()) {
                $curso->alumnos()->updateExistingPivot($alumno->id, $pivotData);
                $res['actualizados'][] = $nombre;
            } else {
                $curso->alumnos()->attach($alumno->id, $pivotData);
                $res[$esNuevo ? 'creados' : 'inscritos'][] = $nombre;
            }
        }

        fclose($handle);

        // Construir mensaje de resumen
        $partes = [];
        if (count($res['creados']))     $partes[] = count($res['creados']) . ' alumno(s) nuevo(s) creado(s)';
        if (count($res['inscritos']))   $partes[] = count($res['inscritos']) . ' inscrito(s)';
        if (count($res['actualizados'])) $partes[] = count($res['actualizados']) . ' actualizado(s)';
        if (count($res['errores']))     $partes[] = count($res['errores']) . ' error(es)';

        $mensaje = 'Importación completada: ' . implode(', ', $partes) . '.';

        return redirect()
            ->route('admin.cursos.alumnos.index', $curso)
            ->with('success', $mensaje)
            ->with('import_errores', $res['errores']);
    }

    // Descarga la plantilla CSV de ejemplo
    public function downloadTemplate(Cursos $curso)
    {
        $this->verificarCurso($curso);

        $callback = function () {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF"); // BOM para que Excel abra en UTF-8
            fputcsv($out, ['nombre_completo', 'username', 'estado', 'fecha_completado']);
            fputcsv($out, ['Juan García López',  'jgarcia', 'completado', '2026-06-15']);
            fputcsv($out, ['María Pérez Torres', 'mperez',  'inscrito',   '']);
            fputcsv($out, ['Carlos Ruiz',        '',        'en_curso',   '']);
            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_alumnos.csv"',
        ]);
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
