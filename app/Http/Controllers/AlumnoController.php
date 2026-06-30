<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:supervisor']);
    }

    /**
     * Display a listing of students (beneficiaries).
     */
    public function index()
    {
        $alumnos = User::role('beneficiario')->with('department')->get();
        return view('supervisor.alumnos.index', compact('alumnos'));
    }

    /**
     * Display the specified student.
     */
    public function show(User $alumno)
    {
        $alumno->load('department');
        return view('supervisor.alumnos.show', compact('alumno'));
    }
}
