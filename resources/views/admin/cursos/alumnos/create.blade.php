{{-- resources/views/admin/cursos/alumnos/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Agregar alumno</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:1fr 300px; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .section-label { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-hint  { font-size:0.73rem; color:#94A3B8; margin-top:0.3rem; }
        .form-input, .form-select {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s, box-shadow 0.15s; background:#fff; box-sizing:border-box;
        }
        .form-input:focus, .form-select:focus {
            border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha);
        }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        /* Modo selector */
        .modo-tabs { display:flex; gap:0; border:1px solid #DDE3EF; border-radius:10px; overflow:hidden; margin-bottom:1.5rem; }
        .modo-tab {
            flex:1; padding:0.65rem; text-align:center; font-size:0.82rem; font-weight:600;
            cursor:pointer; color:#64748b; background:#fff; border:none; transition:background 0.15s, color 0.15s;
        }
        .modo-tab-active { background:var(--brand); color:#fff; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.65rem 1.4rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:0.65rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        .info-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .info-card-header { background:var(--brand-gradient); padding:1.1rem 1.25rem; }
        .info-card-body { padding:1.25rem; }
        .info-step { display:flex; align-items:flex-start; gap:0.75rem; margin-bottom:1rem; }
        .info-step:last-child { margin-bottom:0; }
        .step-num {
            width:24px; height:24px; border-radius:50%; background:var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:0.72rem; font-weight:800; color:var(--brand); flex-shrink:0;
        }
        .step-text { font-size:0.82rem; color:#334155; line-height:1.4; }
        .step-text strong { color:#1E293B; display:block; margin-bottom:2px; }

        .alert-error { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error ul { margin:0.25rem 0 0 1rem; }

        [x-cloak] { display:none !important; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}">{{ $curso->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <span>Agregar</span>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <strong>Corrige los siguientes errores:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="page-grid">

        {{-- Formulario --}}
        <div class="form-card">
            <form method="POST" action="{{ route('admin.cursos.alumnos.store', $curso) }}"
                  x-data="{ modo: '{{ old('modo', 'nuevo') }}', estado: '{{ old('estado', 'inscrito') }}' }">
                @csrf

                {{-- Selector de modo --}}
                <div class="section-label">¿Cómo agregar al alumno?</div>
                <div class="modo-tabs" style="margin-bottom:1.5rem;">
                    <button type="button"
                            :class="modo === 'nuevo' ? 'modo-tab modo-tab-active' : 'modo-tab'"
                            @click="modo = 'nuevo'">
                        Crear alumno nuevo
                    </button>
                    @if($alumnosDisponibles->isNotEmpty())
                        <button type="button"
                                :class="modo === 'existente' ? 'modo-tab modo-tab-active' : 'modo-tab'"
                                @click="modo = 'existente'">
                            Inscribir existente
                        </button>
                    @endif
                </div>
                {{-- Campo oculto para el modo --}}
                <input type="hidden" name="modo" :value="modo">

                {{-- Alumno nuevo --}}
                <div x-show="modo === 'nuevo'" x-cloak>
                    <div class="section-label">Datos del alumno</div>

                    <div class="form-group">
                        <label class="form-label">Nombre completo <span style="color:#DC2626">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}"
                               class="form-input" placeholder="Nombre y apellidos">
                        @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Username
                            <span class="form-hint" style="display:inline;margin-left:4px;margin-top:0;">(también puede ser apodo)</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}"
                               class="form-input" placeholder="ej. jgarcia o elchaka">
                        @error('username')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña inicial <span style="color:#DC2626">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Mínimo 8 caracteres">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Alumno existente --}}
                <div x-show="modo === 'existente'" x-cloak>
                    <div class="section-label">Alumno del departamento</div>

                    @if($alumnosDisponibles->isEmpty())
                        <p style="font-size:0.875rem;color:#94A3B8;margin-bottom:1rem;">
                            Todos los alumnos del departamento ya están inscritos en este curso.
                        </p>
                    @else
                        <div class="form-group">
                            <label class="form-label">Selecciona un alumno <span style="color:#DC2626">*</span></label>
                            <select name="user_id" class="form-select">
                                <option value="">— Elige un alumno —</option>
                                @foreach($alumnosDisponibles as $a)
                                    <option value="{{ $a->id }}" {{ old('user_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->display_name }}
                                        @if($a->full_name && $a->full_name !== $a->username)
                                            ({{ $a->username }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    @endif
                </div>

                {{-- Inscripción --}}
                <div class="section-label" style="margin-top:1.5rem;">Inscripción</div>

                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="estado" x-model="estado" class="form-select">
                        <option value="inscrito">Inscrito</option>
                        <option value="en_curso">En curso</option>
                        <option value="completado">Completado</option>
                        <option value="baja">Baja</option>
                    </select>
                    @error('estado')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div x-show="estado === 'completado'" x-cloak class="form-group">
                    <label class="form-label">Fecha de completado</label>
                    <input type="date" name="fecha_completado" value="{{ old('fecha_completado') }}" class="form-input">
                    @error('fecha_completado')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.679 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.966-1.582L5 18.72M9 9a3 3 0 116 0 3 3 0 01-6 0z"/>
                        </svg>
                        Agregar alumno
                    </button>
                    <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>

        {{-- Panel lateral --}}
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <div style="font-size:0.72rem;color:rgba(255,255,255,0.6);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:4px;">Información</div>
                    <div style="font-size:1rem;font-weight:700;color:#fff;">Dos formas de agregar</div>
                </div>
                <div class="info-card-body">
                    <div class="info-step">
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <strong>Alumno nuevo</strong>
                            Crea una cuenta nueva con nombre, usuario y contraseña inicial.
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <strong>Inscribir existente</strong>
                            Selecciona un alumno ya registrado en el departamento que aún no esté en este curso.
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:1rem;background:var(--brand-bg);border:1px solid var(--brand-alpha);border-radius:12px;padding:1rem 1.1rem;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:0.4rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="2" style="width:15px;height:15px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342"/>
                    </svg>
                    <span style="font-size:0.78rem;font-weight:700;color:var(--brand);">Curso: {{ $curso->nombre }}</span>
                </div>
                <p style="font-size:0.78rem;color:#475569;margin:0;">
                    Solo alumnos con estado <strong>Completado</strong> serán elegibles para recibir diploma.
                </p>
            </div>
        </div>

    </div>

</x-app-layout>
