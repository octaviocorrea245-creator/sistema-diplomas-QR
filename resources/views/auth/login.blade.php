<x-guest-layout>

    {{-- Status --}}
    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field-group">
            <label class="field-label" for="email">Correo electrónico</label>
            <div class="field-wrapper">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="correo@upgp.edu.mx"
                    style="{{ $errors->has('email') ? 'border-color:#c0392b;' : '' }}"
                >
            </div>
            @error('email')
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

        {{-- Recordar / Olvidé --}}
        <div class="row-between">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember_me">
                <span>Recordar sesión</span>
            </label>
            @if (Route::has('password.request'))
                <a class="link-forgot" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        {{-- Crear cuenta --}}
        @if (Route::has('register'))
            <div style="text-align:center; margin-bottom:1.25rem;">
                <span style="font-size:0.82rem; color:#8496b0;">¿No tienes cuenta?</span>
                <a href="{{ route('register') }}" style="font-size:0.82rem; color:#1a56b0; font-weight:600; text-decoration:none; margin-left:0.3rem;">Crear cuenta</a>
            </div>
        @endif

        {{-- Botón --}}
        <button type="submit" class="btn-submit">
            Entrar al sistema
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>

    </form>

</x-guest-layout>
