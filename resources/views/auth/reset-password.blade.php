<x-guest-layout>
    <div class="card-header">
        <p class="step-label">Restablecer</p>
        <h2>Nueva contraseña</h2>
        <p>Ingresa tu nueva contraseña para acceder</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                    readonly
                    placeholder="tu@correo.com"
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

        <div class="field-group">
            <label class="field-label" for="password">Nueva contraseña</label>
            <div class="field-wrapper">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
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

        <div class="field-group">
            <label class="field-label" for="password_confirmation">Confirmar contraseña</label>
            <div class="field-wrapper">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repite la contraseña"
                    style="{{ $errors->has('password_confirmation') ? 'border-color:#c0392b;' : '' }}"
                >
            </div>
            @error('password_confirmation')
                <p class="field-error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">
            Restablecer contraseña
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
    </form>
</x-guest-layout>
