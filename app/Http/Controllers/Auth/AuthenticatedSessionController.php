<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('username');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return match (true) {
            $user->hasRole('supervisor')   => redirect()->route('supervisor.admins.index'),
            $user->hasRole('admin')        => redirect()->route('dashboard'),
            $user->hasRole('beneficiario') => redirect()->route('dashboard'),
            default                        => redirect()->route('dashboard'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}