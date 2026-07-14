{{-- resources/views/admin/cursos/alumnos/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos inscritos</h2>
    </x-slot>

    @php $viewMode = request('view', 'list'); @endphp

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-filter {
            display:inline-flex; align-items:center; gap:6px;
            background:#0D1B35; color:#fff; border:none;
            padding:0.5rem 1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-filter:hover { opacity:0.85; }
        .btn-ghost {
            display:inline-flex; align-items:center;
            padding:0.5rem 1rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.83rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-ghost:hover { background:#F7F9FC; color:#64748b; }

        .filter-bar {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.1rem 1.25rem;
            display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.9rem; margin-bottom:1.5rem;
        }
        .filter-group { display:flex; flex-direction:column; gap:0.3rem; }
        .filter-label { font-size:0.7rem; font-weight:700; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; }
        .filter-input, .filter-select {
            border:1px solid #DDE3EF; border-radius:9px; padding:0.5rem 0.9rem;
            font-size:0.875rem; outline:none; background:#fff; transition:border 0.15s;
        }
        .filter-input:focus, .filter-select:focus { border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .filter-input { min-width:240px; }
        .filter-select { min-width:140px; }

        .view-toggle { display:flex; border:1px solid #DDE3EF; border-radius:9px; overflow:hidden; margin-left:auto; }
        .view-btn {
            display:flex; align-items:center; justify-content:center;
            width:36px; height:36px; border:none; background:#fff; cursor:pointer;
            color:#94A3B8; transition:background 0.1s, color 0.1s; text-decoration:none;
        }
        .view-btn:hover { background:#F7F9FC; color:#64748b; }
        .view-btn.active { background:#0D1B35; color:#fff; }

        .summary-card {
            background:#fff; border:1px solid #E8EDF4; border-radius:12px;
            padding:1rem 1.25rem; margin-bottom:1.25rem;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .summary-info { font-size:0.875rem; color:#475569; }
        .summary-info strong { color:#1E293B; }

        .data-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .data-table thead th {
            background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.7rem 1.1rem; text-align:left;
            font-size:0.7rem; font-weight:600; color:#4A6585;
            letter-spacing:0.07em; text-transform:uppercase;
        }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.75rem 1.1rem; color:#334155; vertical-align:middle; }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-blue   { background:#EFF6FF; color:#1D4ED8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }

        .action-link { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view   { color:var(--brand); }
        .link-edit   { color:#D97706; }
        .link-delete { color:#DC2626; background:none; border:none; cursor:pointer; font-size:0.8rem; font-weight:500; padding:0; }
        .link-delete:hover { opacity:0.7; }

        /* Grid cards */
        .alumno-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:1rem; }
        .alumno-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
            transition:box-shadow 0.15s, transform 0.15s;
        }
        .alumno-card:hover { box-shadow:0 6px 20px var(--brand-alpha); transform:translateY(-2px); }
        .alumno-card-body { padding:1.1rem 1.2rem; }
        .alumno-card-footer { border-top:1px solid #F0F4FA; padding:0.65rem 1.2rem; display:flex; align-items:center; gap:1rem; }
        .avatar-sm {
            width:36px; height:36px; border-radius:50%; background:var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:0.85rem; font-weight:700; color:var(--brand); flex-shrink:0;
        }

        .empty-state { text-align:center; padding:3.5rem 1rem; }
        .empty-state svg { display:block; margin:0 auto 1rem; }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1rem; }
        .alert-warning { background:#FFFBEB; border:1px solid #FDE68A; color:#92400E;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.82rem; margin-bottom:1rem; }
        .btn-outline-sm {
            display:inline-flex; align-items:center; gap:5px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.45rem 0.9rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-outline-sm:hover { background:#F7F9FC; color:#475569; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('import_errores') && count(session('import_errores')))
        <div class="alert-warning">
            <strong>Filas con errores durante la importación:</strong>
            <ul style="margin:0.4rem 0 0 1.1rem;padding:0;">
                @foreach(session('import_errores') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}">{{ $curso->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Alumnos</span>
    </div>

    {{-- Resumen + botón agregar --}}
    <div class="summary-card">
        <div class="summary-info">
            Curso: <strong>{{ $curso->nombre }}</strong> &mdash;
            <strong>{{ $alumnos->total() }}</strong> alumno(s) inscritos
        </div>
        <div style="display:flex;gap:0.6rem;">
            <a href="{{ route('admin.cursos.alumnos.importar', $curso) }}" class="btn-outline-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Importar CSV
            </a>
            <a href="{{ route('admin.cursos.alumnos.create', $curso) }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Agregar alumno
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('admin.cursos.alumnos.index', $curso) }}">
        <div class="filter-bar">
            <div class="filter-group" style="flex:1;min-width:200px;">
                <label class="filter-label">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="filter-input" placeholder="Buscar por nombre o usuario…">
            </div>
            <div class="filter-group">
                <label class="filter-label">Estado</label>
                <select name="estado" class="filter-select">
                    <option value="">Todos</option>
                    <option value="inscrito"   {{ request('estado') === 'inscrito'   ? 'selected' : '' }}>Inscrito</option>
                    <option value="en_curso"   {{ request('estado') === 'en_curso'   ? 'selected' : '' }}>En curso</option>
                    <option value="completado" {{ request('estado') === 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="baja"       {{ request('estado') === 'baja'       ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;gap:0.5rem;">
                <button type="submit" class="btn-filter">Filtrar</button>
                @if(request('search') || request('estado'))
                    <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="btn-ghost">Limpiar</a>
                @endif
            </div>
            <div style="display:flex;align-items:flex-end;margin-left:auto;">
                <div class="view-toggle">
                    <a href="{{ route('admin.cursos.alumnos.index', array_merge(['curso'=>$curso->id], request()->query(), ['view'=>'list'])) }}"
                       class="view-btn {{ $viewMode === 'list' ? 'active' : '' }}" title="Lista">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.cursos.alumnos.index', array_merge(['curso'=>$curso->id], request()->query(), ['view'=>'grid'])) }}"
                       class="view-btn {{ $viewMode === 'grid' ? 'active' : '' }}" title="Cuadrícula">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </form>

    @if($alumnos->isEmpty())
        <div class="data-card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:48px;height:48px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <p style="font-size:0.95rem;color:#64748b;margin-bottom:0.5rem;">
                    @if(request('search') || request('estado'))
                        No hay alumnos que coincidan con la búsqueda
                    @else
                        Sin alumnos inscritos en este curso
                    @endif
                </p>
                @if(request('search') || request('estado'))
                    <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" style="font-size:0.82rem;color:var(--brand);">Limpiar filtros</a>
                @else
                    <a href="{{ route('admin.cursos.alumnos.create', $curso) }}" class="btn-primary" style="margin-top:1rem;display:inline-flex;">Agregar el primero</a>
                @endif
            </div>
        </div>

    @elseif($viewMode === 'list')
        <div class="data-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Inscripción</th>
                        <th>Completado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                        @php
                            $estado = $alumno->pivot->estado;
                            $estadoBadge = match($estado) {
                                'completado' => 'badge-green',
                                'en_curso'   => 'badge-blue',
                                'inscrito'   => 'badge-orange',
                                'baja'       => 'badge-red',
                                default      => 'badge-gray',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="avatar-sm">{{ strtoupper(substr($alumno->display_name, 0, 1)) }}</div>
                                    <span style="font-weight:500;color:#1E293B;">{{ $alumno->display_name }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b;font-size:0.82rem;">{{ $alumno->username ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $estadoBadge }}">{{ ucfirst(str_replace('_',' ',$estado)) }}</span>
                            </td>
                            <td style="font-size:0.82rem;color:#64748b;">
                                {{ $alumno->pivot->created_at?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td style="font-size:0.82rem;color:#64748b;">
                                {{ $alumno->pivot->fecha_completado
                                    ? \Carbon\Carbon::parse($alumno->pivot->fecha_completado)->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;gap:1rem;justify-content:flex-end;align-items:center;">
                                    <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}" class="action-link link-view">Ver</a>
                                    <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}" class="action-link link-edit">Editar</a>
                                    <form method="POST" action="{{ route('admin.cursos.alumnos.destroy', [$curso, $alumno]) }}"
                                          onsubmit="return confirm('¿Dar de baja a {{ $alumno->display_name }} del curso?')" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="link-delete">Baja</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        {{-- Grid --}}
        <div class="alumno-grid">
            @foreach($alumnos as $alumno)
                @php
                    $estado = $alumno->pivot->estado;
                    $estadoBadge = match($estado) {
                        'completado' => 'badge-green',
                        'en_curso'   => 'badge-blue',
                        'inscrito'   => 'badge-orange',
                        'baja'       => 'badge-red',
                        default      => 'badge-gray',
                    };
                @endphp
                <div class="alumno-card">
                    <div class="alumno-card-body">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:0.75rem;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar-sm">{{ strtoupper(substr($alumno->display_name, 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:600;color:#1E293B;font-size:0.875rem;">{{ $alumno->display_name }}</div>
                                    @if($alumno->username)
                                        <div style="font-size:0.72rem;color:#94A3B8;">{{ $alumno->username }}</div>
                                    @endif
                                </div>
                            </div>
                            <span class="badge {{ $estadoBadge }}">{{ ucfirst(str_replace('_',' ',$estado)) }}</span>
                        </div>
                        <div style="font-size:0.75rem;color:#94A3B8;">
                            Inscrito {{ $alumno->pivot->created_at?->format('d/m/Y') ?? '—' }}
                            @if($alumno->pivot->fecha_completado)
                                · Completado {{ \Carbon\Carbon::parse($alumno->pivot->fecha_completado)->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>
                    <div class="alumno-card-footer">
                        <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}" class="action-link link-view">Ver</a>
                        <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}" class="action-link link-edit">Editar</a>
                        <form method="POST" action="{{ route('admin.cursos.alumnos.destroy', [$curso, $alumno]) }}"
                              onsubmit="return confirm('¿Dar de baja a {{ $alumno->display_name }}?')"
                              style="margin:0;margin-left:auto;">
                            @csrf @method('DELETE')
                            <button type="submit" class="link-delete">Baja</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($alumnos->hasPages())
        <div style="margin-top:1.5rem;">{{ $alumnos->links() }}</div>
    @endif

</x-app-layout>
