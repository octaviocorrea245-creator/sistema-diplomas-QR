<x-guest-layout>
    <div class="card-header">
        <p class="step-label">Recuperación</p>
        <h2>¿Olvidaste tu contraseña?</h2>
        <p>Te enviaremos un enlace para restablecerla</p>
    </div>

    @if (session('status'))
        <div class="status-msg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

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

        <button type="submit" class="btn-submit">
            Enviar enlace de recuperación
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
    </form>
</x-guest-layout>
