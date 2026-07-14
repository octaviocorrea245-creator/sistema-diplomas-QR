<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function importForm()
    {
        return view('admin.alumnos.import');
    }

    public function import(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'archivo' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
        ], [
            'archivo.required' => 'Selecciona un archivo CSV.',
            'archivo.mimes'    => 'El archivo debe ser .csv o .txt.',
        ]);

        $handle = fopen($request->file('archivo')->getRealPath(), 'r');

        // Encabezado (quitar BOM)
        $rawHeader = fgetcsv($handle);
        $rawHeader[0] = ltrim($rawHeader[0], "\xEF\xBB\xBF");
        $header = array_map(fn($h) => strtolower(trim($h)), $rawHeader);

        $creados  = [];
        $omitidos = []; // ya existían
        $errores  = [];
        $fila     = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $fila++;
            $data = array_combine($header, array_pad($row, count($header), ''));

            $nombre   = trim($data['nombre_completo'] ?? $data['nombre'] ?? '');
            $username = trim($data['username'] ?? $data['usuario'] ?? '');

            if ($nombre === '') {
                $errores[] = "Fila {$fila}: nombre_completo es obligatorio.";
                continue;
            }

            // ¿Ya existe alguien con ese username o nombre en el departamento?
            $existe = false;
            if ($username !== '' && User::where('username', $username)->exists()) {
                $omitidos[] = "{$nombre} (username duplicado)";
                $existe = true;
            }
            if (!$existe && User::where('full_name', $nombre)
                                 ->where('department_id', $admin->department_id)
                                 ->exists()) {
                $omitidos[] = "{$nombre} (ya existe)";
                $existe = true;
            }
            if ($existe) continue;

            // Generar username único si no viene
            if ($username === '') {
                $base = Str::slug($nombre, '');
                $base = $base ?: 'alumno';
                $username = $base;
                $n = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $base . $n++;
                }
            }

            User::create([
                'full_name'     => $nombre,
                'username'      => $username,
                'password'      => Hash::make('Cambiar@' . rand(1000, 9999)),
                'role'          => 'beneficiario',
                'department_id' => $admin->department_id,
            ]);

            $creados[] = $nombre;
        }

        fclose($handle);

        $partes = [];
        if ($creados)  $partes[] = count($creados) . ' alumno(s) creado(s)';
        if ($omitidos) $partes[] = count($omitidos) . ' omitido(s)';
        if ($errores)  $partes[] = count($errores) . ' error(es)';

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', 'Importación completada: ' . implode(', ', $partes) . '.')
            ->with('import_errores', $errores)
            ->with('import_omitidos', $omitidos);
    }

    public function downloadTemplate()
    {
        $callback = function () {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['nombre_completo', 'username']);
            fputcsv($out, ['Juan García López', 'jgarcia']);
            fputcsv($out, ['María Pérez Torres', 'mperez']);
            fputcsv($out, ['Carlos Ruiz', '']);
            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_alumnos.csv"',
        ]);
    }
}
