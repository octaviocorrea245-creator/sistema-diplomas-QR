<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diploma;
use App\Models\Cursos;
use App\Models\Plantilla;
use App\Models\VersionPlantilla;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiplomaController extends Controller
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
        $diplomas = Diploma::whereHas('curso', fn($q) => $q->where('departamento_id', $this->departamentoId()))
            ->with(['alumno', 'curso', 'versionPlantilla.plantilla', 'emisor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.diplomas.index', compact('diplomas'));
    }

    public function create()
    {
        $cursos = Cursos::where('departamento_id', $this->departamentoId())
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $plantillas = Plantilla::where('department_id', $this->departamentoId())
            ->where('activa', true)
            ->with('versiones')
            ->orderBy('nombre')
            ->get();

        return view('admin.diplomas.create', compact('cursos', 'plantillas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'curso_id'              => 'required|exists:cursos,id',
            'version_plantilla_id'  => 'required|exists:versiones_plantilla,id',
            'alumno_id'            => 'required|exists:users,id',
            'fecha_emision'        => 'required|date',
        ]);

        $curso = Cursos::findOrFail($request->curso_id);
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $alumno = User::findOrFail($request->alumno_id);
        abort_unless($alumno->department_id === $this->departamentoId(), 403);

        $versionPlantilla = VersionPlantilla::findOrFail($request->version_plantilla_id);
        abort_unless($versionPlantilla->plantilla->department_id === $this->departamentoId(), 403);

        $existe = Diploma::where('user_id', $alumno->id)
            ->where('curso_id', $curso->id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['alumno_id' => 'Este alumno ya tiene un diploma para este curso.'])->withInput();
        }

        $folio = 'DIP-' . strtoupper(Str::random(8));
        while (Diploma::where('folio', $folio)->exists()) {
            $folio = 'DIP-' . strtoupper(Str::random(8));
        }

        $tokenQr = Str::uuid()->toString();

        $diploma = Diploma::create([
            'user_id'              => $alumno->id,
            'curso_id'             => $curso->id,
            'version_plantilla_id' => $versionPlantilla->id,
            'emitido_por'          => auth()->id(),
            'folio'                => $folio,
            'token_qr'             => $tokenQr,
            'ruta_pdf'             => 'diplomas/' . $folio . '.pdf',
            'fecha_emision'        => $request->fecha_emision,
            'estado'               => 'emitido',
        ]);

        return redirect()->route('admin.diplomas.show', $diploma)
            ->with('success', "Diploma {$folio} emitido correctamente.");
    }

    public function show(Diploma $diploma)
    {
        abort_unless($diploma->curso->departamento_id === $this->departamentoId(), 403);

        $diploma->load(['alumno', 'curso', 'versionPlantilla.plantilla', 'emisor', 'reimpresiones']);

        return view('admin.diplomas.show', compact('diploma'));
    }

    public function alumnosPorCurso(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $alumnos = $curso->alumnos()
            ->wherePivot('estado', 'completado')
            ->orderBy('full_name')
            ->get();

        return response()->json($alumnos);
    }

    public function versionesPorPlantilla(Plantilla $plantilla)
    {
        abort_unless($plantilla->department_id === $this->departamentoId(), 403);

        $versiones = $plantilla->versiones()->orderBy('version', 'desc')->get();

        return response()->json($versiones);
    }
}
