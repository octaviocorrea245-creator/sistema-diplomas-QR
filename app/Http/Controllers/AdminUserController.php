<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departamento;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:supervisor']);
    }

    public function index()
    {
        $admins = User::role('admin')->with('department')->get();
        return view('supervisor.admins.index', compact('admins'));
    }

    public function create()
    {
        $departments = Departamento::orderBy('name')->get();
        return view('supervisor.admins.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:255', // 1. Cambiado de 'name' a 'full_name'
            'username'      => 'required|string|max:50|unique:users,username|alpha_dash',
            'department_id' => 'required|exists:departamentos,id',
            'password'      => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'full_name'     => $request->full_name, // 2. Cambiado a 'full_name'
            'username'      => $request->username,
            // 3. SE ELIMINÓ la línea del email falso ("@sistema.com") ya que no existe en tu BD
            'password'      => Hash::make($request->password),
            'role'          => 'admin',
            'department_id' => $request->department_id,
        ]);

        $user->assignRole('admin');

        return redirect()->route('supervisor.admins.index')
                         ->with('success', 'Administrador creado correctamente.');
    }

    // ── Editar ───────────────────────────────────────────────
    public function edit(User $admin)
    {
        $departments = Departamento::orderBy('name')->get();
        return view('supervisor.admins.edit', compact('admin', 'departments'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'full_name'     => 'required|string|max:255', // 4. Cambiado de 'name' a 'full_name'
            'username'      => 'required|string|max:50|alpha_dash|unique:users,username,' . $admin->id,
            'department_id' => 'required|exists:departamentos,id',
            'password'      => 'nullable|min:8|confirmed', 
        ]);

        $admin->update([
            'full_name'     => $request->full_name, // 5. Cambiado a 'full_name'
            'username'      => $request->username,
            'department_id' => $request->department_id,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('supervisor.admins.index')
                         ->with('success', 'Administrador actualizado correctamente.');
    }

    // 6. CORRECCIÓN CRÍTICA: Cambiado $user por $admin para que coincida con el parámetro de la ruta {admin}
    public function destroy(User $admin) 
    {
        if ($admin->hasRole('supervisor')) {
            return back()->with('error', 'No puedes eliminar al supervisor.');
        }

        $admin->delete();
        return back()->with('success', 'Administrador eliminado.');
    }
}