<?php

namespace App\Http\Controllers;

use App\Models\Cursos;
use App\Models\Plantilla;
use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $admin = auth()->user();
        $departmentId = $admin->department_id;

        $stats = [
            'cursos'    => Cursos::where('departamento_id', $departmentId)->count(),
            'alumnos'   => User::alumnos()->delDepartamento($departmentId)->count(),
            'plantillas' => Plantilla::where('department_id', $departmentId)->count(),
            'diplomas'  => Diploma::whereHas('curso', fn($q) => $q->where('departamento_id', $departmentId))->count(),
            'templates' => DiplomaTemplate::whereHas('curso', fn($q) => $q->where('departamento_id', $departmentId))->count(),
        ];

        $ultimosCursos = Cursos::where('departamento_id', $departmentId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.index', compact('stats', 'ultimosCursos'));
    }
}
