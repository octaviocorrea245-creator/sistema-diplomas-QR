<x-guest-layout>
    <div class="card-header">
        <p class="step-label">Autenticación</p>
        <h2>Iniciar sesión</h2>
        <p>Ingresa tus credenciales institucionales</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username --}}
        <div class="field-group">
            <label class="field-label" for="username">Usuario</label>
            <div class="field-wrapper">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Tu nombre de usuario"
                    style="{{ $errors->has('username') ? 'border-color:#c0392b;' : '' }}"
                >
            </div>
            @error('username')
                <p class="field-error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="field-group">
            <label class="field-label" for="password">Contraseña</label>
            <div class="field-wrapper">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••••"
                    style="{{ $errors->has('password') ? 'border-color:#c0392b;' : '' }}"
                >
            </div>
            @error('password')
                <p class="field-error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center" style="cursor:pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="accent-color:#1A56B0; width:15px; height:15px; border-radius:4px;">
                <span style="margin-left:0.5rem; font-size:0.82rem; color:#5a6a85;">Recordar sesión</span>
            </label>
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn-submit">
            Entrar al sistema
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>

    </form>

</x-guest-layout>
