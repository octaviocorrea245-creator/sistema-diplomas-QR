<x-app-layout>
    <x-slot name="header">
        <h2>Emitir Diploma</h2>
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

        .alert-error {
            background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem;
        }
        .alert-error ul { margin:0.25rem 0 0 1rem; padding:0; }

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
        <a href="{{ route('admin.diplomas.index') }}">Diplomas</a>
        <span class="breadcrumb-sep">/</span>
        <span>Emitir</span>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.diplomas.store') }}" method="POST" x-data="diplomaForm()">
            @csrf

            <div class="form-group">
                <label class="form-label">Curso <span style="color:#DC2626;">*</span></label>
                <select name="curso_id" x-model="cursoId" x-on:change="cargarDatos()" required class="form-select">
                    <option value="">Seleccionar curso…</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                            {{ $curso->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Plantilla / Versión <span style="color:#DC2626;">*</span></label>
                <select name="version_plantilla_id" required class="form-select">
                    <option value="">Primero selecciona un curso</option>
                    @foreach($plantillas as $plantilla)
                        <optgroup label="{{ $plantilla->nombre }}">
                            @foreach($plantilla->versiones as $version)
                                <option value="{{ $version->id }}"
                                    data-plantilla="{{ $plantilla->id }}"
                                    {{ old('version_plantilla_id') == $version->id ? 'selected' : '' }}>
                                    v{{ $version->version }} {{ $version->activa ? '(Activa)' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alumno <span style="color:#DC2626;">*</span></label>
                <select name="alumno_id" required class="form-select">
                    <option value="">Primero selecciona un curso</option>
                </select>
                <p class="form-hint">Solo aparecen alumnos con estado "completado" en el curso.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha de emisión <span style="color:#DC2626;">*</span></label>
                <input type="date" name="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}"
                       class="form-input" required>
            </div>

            <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                    Emitir diploma
                </button>
                <a href="{{ route('admin.diplomas.index') }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function diplomaForm() {
            return {
                cursoId: '{{ old('curso_id') }}',
                cargarDatos() {
                    if (!this.cursoId) return;
                    fetch(`/admin/alumnos-por-curso/${this.cursoId}`)
                        .then(r => r.json())
                        .then(alumnos => {
                            const sel = document.querySelector('select[name="alumno_id"]');
                            sel.innerHTML = '<option value="">Seleccionar alumno</option>';
                            alumnos.forEach(a => {
                                sel.innerHTML += `<option value="${a.id}">${a.full_name}</option>`;
                            });
                        });
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const sel = document.querySelector('select[name="curso_id"]');
            if (sel && sel.value) sel.dispatchEvent(new Event('change'));
        });
    </script>
    @endpush

</x-app-layout>
