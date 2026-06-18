<x-app-layout>
    <x-slot name="header">
        <h2>Editar Alumno</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; transition:color 0.1s; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; max-width:520px; }
        .form-group { margin-bottom:1.25rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-input {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s; box-sizing:border-box;
        }
        .form-input:focus { border-color: var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.6rem 1.3rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:0.6rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.alumnos.index') }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.alumnos.show', $alumno) }}">{{ $alumno->full_name }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Editar</span>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.alumnos.update', $alumno) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="full_name" value="{{ old('full_name', $alumno->full_name) }}"
                       class="form-input">
                @error('full_name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" value="{{ old('username', $alumno->username) }}"
                       class="form-input">
                @error('username')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                <button type="submit" class="btn-primary">Actualizar</button>
                <a href="{{ route('admin.alumnos.show', $alumno) }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

</x-app-layout>
