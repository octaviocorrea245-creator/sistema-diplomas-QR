<x-app-layout>
    <x-slot name="header">
        <h2>Nuevo Administrador</h2>
    </x-slot>

    <style>
        .form-field { margin-bottom:1.25rem; }
        .form-label { display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem; }
        .form-input { width:100%; padding:0.6rem 0.875rem; border:1.5px solid #D1D5DB; border-radius:8px; font-size:0.875rem; color:#1e293b; outline:none; transition:border-color 0.15s; font-family:inherit; box-sizing:border-box; }
        .form-input:focus { border-color:#1A56B0; }
        .form-input.error { border-color:#C0392B; }
        .form-error { color:#C0392B; font-size:0.8rem; margin-top:0.35rem; }
        .form-select { width:100%; padding:0.6rem 0.875rem; border:1.5px solid #D1D5DB; border-radius:8px; font-size:0.875rem; color:#1e293b; outline:none; transition:border-color 0.15s; font-family:inherit; box-sizing:border-box; background:#fff; }
        .form-select:focus { border-color:#1A56B0; }
    </style>

    <div style="max-width:560px;">
        <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9;">
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Crear Administrador</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">Completa los datos del nuevo usuario administrador</p>
            </div>

            <form action="{{ route('supervisor.admins.store') }}" method="POST" style="padding:1.5rem;">
                @csrf

                <div class="form-field">
                    <label class="form-label">Nombre completo <span style="color:#C0392B;">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Ej. Juan Pérez"
                           class="form-input {{ $errors->has('full_name') ? 'error' : '' }}">
                    @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-field">
                    <label class="form-label">Nombre de usuario <span style="color:#C0392B;">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Ej. jperez"
                           class="form-input {{ $errors->has('username') ? 'error' : '' }}">
                    @error('username') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-field">
                    <label class="form-label">Departamento</label>
                    <select name="department_id" class="form-select {{ $errors->has('department_id') ? 'error' : '' }}">
                        <option value="">— Sin departamento —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="border-top:1px solid #F1F5F9; padding-top:1.25rem; margin-top:0.25rem;">
                    <p style="font-size:0.8rem; color:#94a3b8; margin:0 0 1rem;">Contraseña de acceso</p>

                    <div class="form-field">
                        <label class="form-label">Contraseña <span style="color:#C0392B;">*</span></label>
                        <input type="password" name="password" placeholder="Mínimo 8 caracteres"
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-field">
                        <label class="form-label">Confirmar contraseña <span style="color:#C0392B;">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Repite la contraseña"
                               class="form-input">
                    </div>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:6px; background:#1A56B0; color:#fff; padding:0.55rem 1.25rem; border-radius:8px; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; transition:background 0.15s;"
                            onmouseover="this.style.background='#1547A0'" onmouseout="this.style.background='#1A56B0'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Crear Administrador
                    </button>
                    <a href="{{ route('supervisor.admins.index') }}"
                       style="display:inline-flex; align-items:center; color:#64748b; padding:0.55rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:500; border:1.5px solid #E2E8F0; text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
