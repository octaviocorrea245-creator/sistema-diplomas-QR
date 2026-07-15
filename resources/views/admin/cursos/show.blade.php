<x-app-layout>
    <x-slot name="header">
        <h2>{{ $cursos->nombre }}</h2>
    </x-slot>

    <style>
        .page-wrap { max-width:900px; margin:0 auto; }

        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; }
        .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        /* ── Cards ─────────────────────────────── */
        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .card-header {
            display:flex; align-items:center; gap:10px;
            padding:0.9rem 1.25rem; border-bottom:1px solid #F0F4FA; background:#F8FAFC;
        }
        .card-header-brand { background:var(--brand-gradient); padding:1rem 1.25rem; display:flex; align-items:center; gap:10px; }
        .card-body { padding:1.5rem; }

        /* ── Meta grid ──────────────────────────── */
        .meta-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:0.75rem; }
        @media(max-width:640px){ .meta-grid { grid-template-columns:repeat(2,1fr); } }
        .meta-lbl { font-size:0.68rem; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:3px; }
        .meta-val { font-size:0.875rem; color:#1E293B; font-weight:600; }

        /* ── Status badge ───────────────────────── */
        .badge-estado { display:inline-flex; align-items:center; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.72rem; font-weight:700; }
        .badge-activo    { background:#F0FDF4; color:#16A34A; border:1px solid #BBF7D0; }
        .badge-borrador  { background:#F1F5F9; color:#64748B; border:1px solid #E2E8F0; }
        .badge-finalizado{ background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; }
        .badge-cancelado { background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; }

        /* ── Stat cards ─────────────────────────── */
        .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:0.75rem; margin-bottom:1.25rem; }
        @media(max-width:640px){ .stat-grid { grid-template-columns:repeat(2,1fr); } }
        .stat-card { background:#fff; border-radius:12px; border:1px solid #E8EDF4;
            padding:1rem 1rem 0.85rem; text-decoration:none;
            transition:box-shadow 0.15s, transform 0.15s; display:block; }
        .stat-card:hover { box-shadow:0 4px 14px rgba(0,0,0,0.08); transform:translateY(-1px); }
        .stat-number { font-size:1.6rem; font-weight:800; line-height:1; }
        .stat-label  { font-size:0.68rem; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:0.07em; margin-top:4px; }

        /* ── Action buttons ─────────────────────── */
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.6rem 1.25rem; border-radius:9px; font-size:0.82rem; font-weight:600;
            cursor:pointer; text-decoration:none; transition:opacity 0.15s; white-space:nowrap;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-outline {
            display:inline-flex; align-items:center; gap:7px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.6rem 1.25rem; border-radius:9px; font-size:0.82rem; font-weight:600;
            text-decoration:none; transition:background 0.1s; white-space:nowrap;
        }
        .btn-outline:hover { background:#F7F9FC; color:#475569; }

        /* ── Template action buttons ─────────────── */
        .btn-sm { display:inline-flex; align-items:center; gap:5px;
            padding:0.4rem 0.85rem; border-radius:7px; font-size:0.78rem; font-weight:600;
            text-decoration:none; transition:opacity 0.12s, background 0.1s; white-space:nowrap; }
        .btn-sm-brand  { background:var(--brand); color:#fff; border:none; cursor:pointer; }
        .btn-sm-brand:hover { opacity:0.87; color:#fff; }
        .btn-sm-green  { background:#F0FDF4; color:#15803D; border:1px solid #BBF7D0; }
        .btn-sm-green:hover { background:#DCFCE7; }
        .btn-sm-blue   { background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; }
        .btn-sm-blue:hover { background:#DBEAFE; }
        .btn-sm-gray   { background:#F1F5F9; color:#475569; border:1px solid #E2E8F0; }
        .btn-sm-gray:hover { background:#E2E8F0; }
        .btn-sm-red    { background:#fff; color:#DC2626; border:1px solid #FECACA; cursor:pointer; }
        .btn-sm-red:hover  { background:#FEF2F2; }

        /* ── Alerts ──────────────────────────────── */
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }

        /* ── Empty state ─────────────────────────── */
        .empty-state { text-align:center; padding:2.5rem 1rem; }
        .empty-icon { width:44px; height:44px; margin:0 auto 0.75rem; color:#CBD5E1; }
        .empty-title { font-size:0.95rem; font-weight:600; color:#374151; margin-bottom:0.25rem; }
        .empty-desc  { font-size:0.82rem; color:#94A3B8; }
    </style>

    <div class="page-wrap">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('admin.cursos.index') }}">Cursos</a>
            <span class="breadcrumb-sep">/</span>
            <span>{{ $cursos->nombre }}</span>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @php $toast = session('toast'); @endphp
        @if($toast)
            <div class="{{ $toast['type'] === 'success' ? 'alert-success' : 'alert-error' }}">
                {{ $toast['message'] }}
            </div>
        @endif

        {{-- ── Info del curso ─────────────────────────── --}}
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                     style="width:16px;height:16px;color:#64748b;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span style="font-size:0.875rem;font-weight:700;color:#374151;flex:1;">{{ $cursos->nombre }}</span>
                @php
                    $badgeClass = match($cursos->estado) {
                        'activo'     => 'badge-activo',
                        'borrador'   => 'badge-borrador',
                        'finalizado' => 'badge-finalizado',
                        'cancelado'  => 'badge-cancelado',
                        default      => 'badge-borrador',
                    };
                @endphp
                <span class="badge-estado {{ $badgeClass }}">{{ ucfirst($cursos->estado) }}</span>
            </div>

            <div class="card-body">
                @if($cursos->descripcion)
                    <p style="font-size:0.875rem;color:#475569;margin-bottom:1.25rem;line-height:1.6;">
                        {{ $cursos->descripcion }}
                    </p>
                @endif
                <div class="meta-grid">
                    <div>
                        <div class="meta-lbl">Departamento</div>
                        <div class="meta-val">{{ $cursos->departamento->name }}</div>
                    </div>
                    <div>
                        <div class="meta-lbl">Horas</div>
                        <div class="meta-val">{{ $cursos->horas ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="meta-lbl">Inicio</div>
                        <div class="meta-val">
                            {{ $cursos->fecha_inicio ? \Carbon\Carbon::parse($cursos->fecha_inicio)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="meta-lbl">Fin</div>
                        <div class="meta-val">
                            {{ $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Estadísticas ────────────────────────── --}}
        <div class="stat-grid">
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'inscrito']) }}"
               class="stat-card">
                <div class="stat-number" style="color:#2563EB;">{{ $stats['inscrito'] }}</div>
                <div class="stat-label">Inscritos</div>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'en_curso']) }}"
               class="stat-card">
                <div class="stat-number" style="color:#D97706;">{{ $stats['en_curso'] }}</div>
                <div class="stat-label">En curso</div>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'completado']) }}"
               class="stat-card">
                <div class="stat-number" style="color:#16A34A;">{{ $stats['completado'] }}</div>
                <div class="stat-label">Completados</div>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'baja']) }}"
               class="stat-card">
                <div class="stat-number" style="color:#DC2626;">{{ $stats['baja'] }}</div>
                <div class="stat-label">Bajas</div>
            </a>
        </div>

        {{-- ── Acciones rápidas ─────────────────────── --}}
        <div style="display:flex;flex-wrap:wrap;gap:0.6rem;margin-bottom:1.25rem;">
            <a href="{{ route('admin.cursos.alumnos.index', $cursos) }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                Gestionar Alumnos
            </a>
            <a href="{{ route('admin.cursos.edit', $cursos) }}" class="btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
                Editar Curso
            </a>
            <a href="{{ route('admin.cursos.index') }}" class="btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                </svg>
                Volver
            </a>
        </div>

        {{-- ── Plantilla de Diploma ─────────────────── --}}
        @php $template = $cursos->template; @endphp
        <div class="card">
            <div class="card-header-brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.8"
                     style="width:16px;height:16px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span style="font-size:0.82rem;font-weight:700;color:#fff;flex:1;">Plantilla de Diploma</span>
                @if($template)
                    <span style="font-size:0.7rem;background:rgba(255,255,255,0.2);color:#fff;padding:0.2rem 0.65rem;border-radius:20px;font-weight:600;">
                        Configurada
                    </span>
                @else
                    <span style="font-size:0.7rem;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.7);padding:0.2rem 0.65rem;border-radius:20px;">
                        Sin plantilla
                    </span>
                @endif
            </div>

            @if($template)
                <div class="card-body">
                    <div style="display:flex;gap:1.25rem;align-items:flex-start;">
                        @if($template->background_image)
                            <div style="flex-shrink:0;width:140px;height:90px;border-radius:10px;overflow:hidden;border:1px solid #E8EDF4;">
                                <img src="{{ Storage::url($template->background_image) }}"
                                     style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        @else
                            <div style="flex-shrink:0;width:140px;height:90px;border-radius:10px;border:2px dashed #DDE3EF;background:#F8FAFC;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:0.72rem;color:#94A3B8;">Sin fondo</span>
                            </div>
                        @endif

                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.975rem;font-weight:700;color:#1E293B;margin-bottom:0.3rem;">
                                {{ $template->nombre }}
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:1rem;font-size:0.78rem;color:#64748b;margin-bottom:0.85rem;">
                                <span>{{ $template->canvas_width }} × {{ $template->canvas_height }} px</span>
                                <span>{{ $template->elements->count() }} elemento(s)</span>
                                <span>Creado {{ $template->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                                <a href="{{ route('admin.templates.editor', $template) }}" class="btn-sm btn-sm-brand">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/>
                                    </svg>
                                    Diseñar
                                </a>
                                <a href="{{ route('admin.diplomas.mass.create', ['curso_id' => $cursos->id]) }}" class="btn-sm btn-sm-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Generar Diplomas
                                </a>
                                <a href="{{ route('admin.diplomas.mass.show', $cursos) }}" class="btn-sm btn-sm-blue">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ver Diplomas
                                </a>
                                <a href="{{ route('admin.templates.show', $template) }}" class="btn-sm btn-sm-gray">
                                    Vista previa
                                </a>
                                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta plantilla y todos sus elementos?')"
                                      style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-sm-red">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p class="empty-title">Sin plantilla de diploma</p>
                    <p class="empty-desc" style="margin-bottom:1rem;">Este curso aún no tiene una plantilla configurada.</p>
                    <a href="{{ route('admin.templates.create') }}" class="btn-primary" style="margin:0 auto;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Crear Plantilla
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
