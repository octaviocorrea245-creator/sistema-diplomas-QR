<x-app-layout>
    <x-slot name="header">
        <h2>Cursos</h2>
    </x-slot>

    @php $viewMode = request('view', 'grid'); @endphp

    <style>
        /* ── Buttons ── */
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-filter {
            display:inline-flex; align-items:center; gap:6px;
            background:#0D1B35; color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-filter:hover { opacity:0.85; }
        .btn-ghost {
            display:inline-flex; align-items:center;
            padding:0.55rem 1rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.83rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-ghost:hover { background:#F7F9FC; color:#64748b; }

        /* ── Filter bar ── */
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
        .filter-input:focus, .filter-select:focus { border-color: var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .filter-input { min-width:260px; }
        .filter-select { min-width:140px; }

        /* ── View toggle ── */
        .view-toggle { display:flex; border:1px solid #DDE3EF; border-radius:9px; overflow:hidden; margin-left:auto; }
        .view-btn {
            display:flex; align-items:center; justify-content:center;
            width:36px; height:36px; border:none; background:#fff; cursor:pointer;
            color:#94A3B8; transition:background 0.1s, color 0.1s; text-decoration:none;
        }
        .view-btn:hover { background:#F7F9FC; color:#64748b; }
        .view-btn.active { background:#0D1B35; color:#fff; }

        /* ── Badges ── */
        .badge { display:inline-flex; align-items:center; padding:0.18rem 0.6rem; border-radius:20px; font-size:0.7rem; font-weight:600; white-space:nowrap; }
        .badge-green    { background:#F0FDF4; color:#16A34A; }
        .badge-gray     { background:#F1F5F9; color:#64748B; }
        .badge-blue     { background:#EFF6FF; color:#2563EB; }
        .badge-red      { background:#FEF2F2; color:#DC2626; }
        .badge-brand    { background: var(--brand-bg); color: var(--brand); border:1px solid var(--brand-alpha); }

        /* ── Grid cards ── */
        .curso-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:1.1rem; }
        .curso-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
            transition:box-shadow 0.15s, transform 0.15s; display:flex; flex-direction:column;
        }
        .curso-card:hover { box-shadow:0 6px 20px var(--brand-alpha); transform:translateY(-2px); }
        .curso-card-accent {
            height:4px; background: var(--brand-gradient);
        }
        .curso-card-body { padding:1.1rem 1.2rem; flex:1; display:flex; flex-direction:column; }
        .curso-card-title { font-size:0.93rem; font-weight:700; color:#1E293B; margin-bottom:0.3rem; line-height:1.3; }
        .curso-card-desc { font-size:0.78rem; color:#94A3B8; margin-bottom:0.75rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; flex:1; }
        .curso-card-meta { display:flex; flex-wrap:wrap; gap:0.4rem 0.9rem; font-size:0.75rem; color:#64748b; margin-bottom:0.9rem; }
        .curso-card-meta span { display:flex; align-items:center; gap:4px; }
        .curso-card-footer { border-top:1px solid #F0F4FA; padding:0.65rem 1.2rem; display:flex; align-items:center; gap:1rem; }

        /* ── List view ── */
        .data-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .data-table thead th { background:#F7F9FC; border-bottom:1px solid #E8EDF4; padding:0.7rem 1.1rem; text-align:left; font-size:0.72rem; font-weight:600; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.75rem 1.1rem; color:#334155; }

        .action-link { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view   { color: var(--brand); }
        .link-edit   { color:#D97706; }
        .link-alum   { color:#7C3AED; }
        .link-delete { color:#DC2626; background:none; border:none; cursor:pointer; font-size:0.8rem; font-weight:500; padding:0; }
        .link-delete:hover { opacity:0.7; }

        /* ── Empty state ── */
        .empty-state { text-align:center; padding:3.5rem 1rem; background:#fff; border-radius:14px; border:1px solid #E8EDF4; }
        .empty-state svg { display:block; margin:0 auto 1rem; }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Page header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem;">
        <div>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">
                {{ $cursos->count() }} curso(s) en total
            </p>
        </div>
        <a href="{{ route('admin.cursos.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Nuevo Curso
        </a>
    </div>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.cursos.index') }}">
        <div class="filter-bar">
            <div class="filter-group" style="flex:1; min-width:220px;">
                <label class="filter-label">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="filter-input" placeholder="Buscar por nombre…">
            </div>
            <div class="filter-group">
                <label class="filter-label">Estado</label>
                <select name="estado" class="filter-select">
                    <option value="">Todos</option>
                    <option value="borrador"   {{ request('estado') === 'borrador'   ? 'selected' : '' }}>Borrador</option>
                    <option value="activo"     {{ request('estado') === 'activo'     ? 'selected' : '' }}>Activo</option>
                    <option value="finalizado" {{ request('estado') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado"  {{ request('estado') === 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div style="display:flex; align-items:flex-end; gap:0.5rem;">
                <button type="submit" class="btn-filter">Filtrar</button>
                @if(request('search') || request('estado'))
                    <a href="{{ route('admin.cursos.index') }}" class="btn-ghost">Limpiar</a>
                @endif
            </div>
            <div style="display:flex; align-items:flex-end; margin-left:auto;">
                <div class="view-toggle">
                    <a href="{{ route('admin.cursos.index', array_merge(request()->query(), ['view'=>'grid'])) }}"
                       class="view-btn {{ $viewMode === 'grid' ? 'active' : '' }}" title="Vista cuadrícula">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.cursos.index', array_merge(request()->query(), ['view'=>'list'])) }}"
                       class="view-btn {{ $viewMode === 'list' ? 'active' : '' }}" title="Vista lista">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Results --}}
    @if($cursos->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:52px;height:52px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            <p style="font-size:0.95rem; color:#64748b; margin-bottom:0.3rem;">
                @if(request('search') || request('estado'))
                    No hay cursos que coincidan con la búsqueda
                @else
                    Aún no tienes cursos creados
                @endif
            </p>
            @if(request('search') || request('estado'))
                <a href="{{ route('admin.cursos.index') }}" style="font-size:0.82rem; color: var(--brand);">Limpiar filtros</a>
            @else
                <a href="{{ route('admin.cursos.create') }}" class="btn-primary" style="margin-top:1rem; display:inline-flex;">Crear primer curso</a>
            @endif
        </div>

    @elseif($viewMode === 'list')
        {{-- ── List view ── --}}
        <div class="data-card">
                    <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Plantilla</th>
                        <th>Horas</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $curso)
                    @php
                        $badgeClass = match($curso->estado) {
                            'activo'     => 'badge-green',
                            'finalizado' => 'badge-blue',
                            'cancelado'  => 'badge-red',
                            default      => 'badge-gray',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#1E293B;">{{ $curso->nombre }}</div>
                        </td>
                        <td>
                            @if($curso->template)
                                <span class="badge badge-green">Creada</span>
                            @else
                                <span class="badge badge-gray">Sin plantilla</span>
                            @endif
                        </td>
                        <td style="color:#64748b;">{{ $curso->horas ? $curso->horas.' h' : '—' }}</td>
                        <td style="font-size:0.78rem; color:#64748b;">
                            @if($curso->fecha_inicio)
                                {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                @if($curso->fecha_fin) – {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }} @endif
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($curso->estado) }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:flex; gap:0.75rem; justify-content:flex-end; align-items:center; flex-wrap:wrap;">
                                <a href="{{ route('admin.cursos.show', $curso) }}" class="action-link link-view">Ver</a>
                                <a href="{{ route('admin.cursos.edit', $curso) }}" class="action-link link-edit">Editar</a>
                                @if($curso->template)
                                    <a href="{{ route('admin.templates.editor', $curso->template) }}" class="action-link link-edit">Diseñar</a>
                                    <a href="{{ route('admin.diplomas.mass.show', ['curso' => $curso->id, 'template' => $curso->template->id]) }}" class="action-link link-alum">Generar</a>
                                @endif
                                <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar «{{ $curso->nombre }}»?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-delete">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        {{-- ── Grid view ── --}}
        <div class="curso-grid">
            @foreach($cursos as $curso)
            @php
                $badgeClass = match($curso->estado) {
                    'activo'     => 'badge-green',
                    'finalizado' => 'badge-blue',
                    'cancelado'  => 'badge-red',
                    default      => 'badge-gray',
                };
            @endphp
            <div class="curso-card">
                <div class="curso-card-accent"></div>
                <div class="curso-card-body">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.5rem; margin-bottom:0.5rem;">
                        <div class="curso-card-title">{{ $curso->nombre }}</div>
                        <span class="badge {{ $badgeClass }}" style="flex-shrink:0;">{{ ucfirst($curso->estado) }}</span>
                    </div>

                    <div style="margin-bottom:0.5rem;">
                        @if($curso->template)
                            <span class="badge badge-green">Plantilla Creada</span>
                        @else
                            <span class="badge badge-gray">Sin plantilla</span>
                        @endif
                    </div>

                    <p class="curso-card-desc">{{ $curso->descripcion ?: 'Sin descripción' }}</p>

                    <div class="curso-card-meta">
                        @if($curso->horas)
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $curso->horas }} h
                        </span>
                        @endif
                        @if($curso->fecha_inicio)
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                        </span>
                        @endif
                        @if($curso->departamento)
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                            {{ $curso->departamento->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="curso-card-footer">
                    <a href="{{ route('admin.cursos.show', $curso) }}" class="action-link link-view">Ver</a>
                    <a href="{{ route('admin.cursos.edit', $curso) }}" class="action-link link-edit">Editar</a>
                    @if($curso->template)
                        <a href="{{ route('admin.templates.editor', $curso->template) }}" class="action-link link-edit">Diseñar</a>
                        <a href="{{ route('admin.diplomas.mass.show', ['curso' => $curso->id, 'template' => $curso->template->id]) }}" class="action-link link-alum">Generar</a>
                    @endif
                    <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar «{{ $curso->nombre }}»?')"
                          style="margin:0; margin-left:auto;">
                        @csrf @method('DELETE')
                        <button type="submit" class="link-delete">Eliminar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
