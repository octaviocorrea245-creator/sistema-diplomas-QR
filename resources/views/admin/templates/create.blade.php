<x-app-layout>
    <x-slot name="header">
        <h2>Nueva Plantilla</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; transition:color 0.1s; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; max-width:600px; }
        .form-group { margin-bottom:1.25rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-hint  { font-size:0.73rem; color:#94A3B8; margin-top:0.3rem; }
        .form-input, .form-select {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s; box-sizing:border-box; background:#fff;
        }
        .form-input:focus, .form-select:focus { border-color: var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        .col-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }

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
        <a href="{{ route('admin.templates.index') }}">Plantillas</a>
        <span class="breadcrumb-sep">/</span>
        <span>Nueva</span>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.templates.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Curso <span style="color:#DC2626;">*</span></label>
                <select name="curso_id" required class="form-select">
                    <option value="">Seleccionar curso…</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" @selected(old('curso_id') == $curso->id)>
                            {{ $curso->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('curso_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nombre de la plantilla <span style="color:#DC2626;">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="255"
                       class="form-input" placeholder="Ej: Plantilla Diploma Curso 2025">
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="col-grid form-group">
                <div>
                    <label class="form-label">Ancho (px)</label>
                    <input type="number" name="canvas_width" value="{{ old('canvas_width', 1920) }}" required
                           min="100" max="4000" class="form-input">
                </div>
                <div>
                    <label class="form-label">Alto (px)</label>
                    <input type="number" name="canvas_height" value="{{ old('canvas_height', 1358) }}" required
                           min="100" max="4000" class="form-input">
                </div>
            </div>
            <p class="form-hint" style="margin-top:-0.75rem; margin-bottom:1.25rem;">1920 × 1358 px = tamaño carta horizontal.</p>

            <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                <button type="submit" class="btn-primary">Crear plantilla</button>
                <a href="{{ route('admin.templates.index') }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

</x-app-layout>
