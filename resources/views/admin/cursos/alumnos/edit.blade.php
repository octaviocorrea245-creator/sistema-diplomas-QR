{{-- resources/views/admin/cursos/alumnos/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Editar inscripción</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:1fr 280px; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .section-label { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-select, .form-input {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s, box-shadow 0.15s; background:#fff; box-sizing:border-box;
        }
        .form-select:focus, .form-input:focus {
            border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha);
        }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

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

        .info-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .info-card-body { padding:1.25rem; }
        .avatar {
            width:48px; height:48px; border-radius:50%; background:var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; font-weight:700; color:var(--brand);
        }
        .info-row { display:flex; gap:8px; margin-bottom:0.6rem; font-size:0.82rem; }
        .info-key { color:#94A3B8; min-width:80px; flex-shrink:0; }
        .info-val { color:#334155; font-weight:500; }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-blue   { background:#EFF6FF; color:#1D4ED8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }

        [x-cloak] { display:none !important; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}">{{ $curso->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}">{{ $alumno->display_name }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Editar</span>
    </div>

    <div class="page-grid">

        {{-- Formulario --}}
        <div class="form-card">
            <div class="section-label">Datos de inscripción</div>

            <form method="POST" action="{{ route('admin.cursos.alumnos.update', [$curso, $alumno]) }}"
                  x-data="{ estado: '{{ old('estado', $inscripcion->pivot->estado) }}' }">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="estado" x-model="estado" class="form-select">
                        <option value="inscrito"   :selected="estado === 'inscrito'">Inscrito</option>
                        <option value="en_curso"   :selected="estado === 'en_curso'">En curso</option>
                        <option value="completado" :selected="estado === 'completado'">Completado</option>
                        <option value="baja"       :selected="estado === 'baja'">Baja</option>
                    </select>
                    @error('estado')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div x-show="estado === 'completado'" x-cloak class="form-group">
                    <label class="form-label">Fecha de completado</label>
                    <input type="date" name="fecha_completado"
                           value="{{ old('fecha_completado', $inscripcion->pivot->fecha_completado?->format('Y-m-d')) }}"
                           class="form-input">
                    @error('fecha_completado')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>

        {{-- Panel lateral: info del alumno --}}
        <div>
            <div class="info-card">
                <div class="info-card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #F0F4FA;">
                        @if($alumno->avatar_url)
                            <img src="{{ $alumno->avatar_url }}" alt="Avatar" class="avatar" style="object-fit:cover;">
                        @else
                            <div class="avatar">{{ strtoupper(substr($alumno->display_name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div style="font-weight:700;color:#1E293B;font-size:0.95rem;">{{ $alumno->display_name }}</div>
                            @if($alumno->username)
                                <div style="font-size:0.78rem;color:#94A3B8;">{{ $alumno->username }}</div>
                            @endif
                        </div>
                    </div>

                    <div style="font-size:0.7rem;font-weight:700;color:#4A6585;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:0.75rem;">Inscripción actual</div>

                    @php
                        $estadoActual = $inscripcion->pivot->estado;
                        $estadoBadge = match($estadoActual) {
                            'completado' => 'badge-green',
                            'en_curso'   => 'badge-blue',
                            'inscrito'   => 'badge-orange',
                            'baja'       => 'badge-red',
                            default      => 'badge-gray',
                        };
                    @endphp

                    <div class="info-row">
                        <span class="info-key">Estado</span>
                        <span class="badge {{ $estadoBadge }}">{{ ucfirst(str_replace('_',' ',$estadoActual)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Inscrito</span>
                        <span class="info-val">{{ $inscripcion->pivot->created_at?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Completado</span>
                        <span class="info-val">
                            {{ $inscripcion->pivot->fecha_completado
                                ? \Carbon\Carbon::parse($inscripcion->pivot->fecha_completado)->format('d/m/Y')
                                : '—' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Curso</span>
                        <span class="info-val" style="font-size:0.78rem;">{{ $curso->nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Horas</span>
                        <span class="info-val">{{ $curso->horas ?? '—' }} h</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
