<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DisenadorController extends Controller
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
        $disenadores = User::role('diseñador')
            ->where('department_id', $this->departamentoId())
            ->with('department')
            ->orderBy('full_name')
            ->get();

        return view('admin.disenadores.index', compact('disenadores'));
    }

    public function create()
    {
        return view('admin.disenadores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:50|unique:users,username|alpha_dash',
            'password'  => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'full_name'     => $request->full_name,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'role'          => 'diseñador',
            'department_id' => $this->departamentoId(),
        ]);

        $user->assignRole('diseñador');

        return redirect()->route('admin.disenadores.index')
                         ->with('success', 'Diseñador creado correctamente.');
    }

    public function edit(User $disenador)
    {
        abort_unless($disenador->hasRole('diseñador'), 403);
        abort_unless($disenador->department_id === $this->departamentoId(), 403);

        return view('admin.disenadores.edit', compact('disenador'));
    }

    public function update(Request $request, User $disenador)
    {
        abort_unless($disenador->hasRole('diseñador'), 403);
        abort_unless($disenador->department_id === $this->departamentoId(), 403);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:50|alpha_dash|unique:users,username,' . $disenador->id,
            'password'  => 'nullable|min:8|confirmed',
        ]);

        $disenador->update([
            'full_name' => $request->full_name,
            'username'  => $request->username,
        ]);

        if ($request->filled('password')) {
            $disenador->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.disenadores.index')
                         ->with('success', 'Diseñador actualizado correctamente.');
    }

    public function destroy(User $disenador)
    {
        abort_unless($disenador->hasRole('diseñador'), 403);
        abort_unless($disenador->department_id === $this->departamentoId(), 403);

        $disenador->delete();

        return back()->with('success', 'Diseñador eliminado.');
    }
}
