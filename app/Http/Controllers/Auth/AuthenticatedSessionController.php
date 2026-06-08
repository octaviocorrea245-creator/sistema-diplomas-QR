<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
<<<<<<< HEAD
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
=======
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
>>>>>>> master
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
<<<<<<< HEAD
    /**
     * Display the login view.
     */
=======
>>>>>>> master
    public function create(): View
    {
        return view('auth.login');
    }

<<<<<<< HEAD
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
=======
    public function store(Request $request): RedirectResponse
    {
        // Validar campos
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Buscar usuario por username
        $user = User::where('username', $request->username)->first();

        // Verificar si existe y la contraseña es correcta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('username');
        }

        // Iniciar sesión
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirigir según rol
        return match(true) {
            $user->hasRole('supervisor')   => redirect()->route('supervisor.admins.index'),
            $user->hasRole('admin')        => redirect()->route('dashboard'),
            $user->hasRole('beneficiario') => redirect()->route('dashboard'),
            default                        => redirect()->route('dashboard'),
        };
    }

>>>>>>> master
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
<<<<<<< HEAD

=======
>>>>>>> master
        $request->session()->regenerateToken();

        return redirect('/');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> master
