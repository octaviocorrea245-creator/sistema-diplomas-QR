<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos</h2>
    </x-slot>

    @php
    $deptColor = function($name) {
        $n = mb_strtolower($name ?? '');
        if (str_contains($n, 'animac'))      return ['accent'=>'#F5A623','bg'=>'#FEF9EC','abbr'=>'IAEV'];
        if (str_contains($n, 'biotecn'))     return ['accent'=>'#7EC441','bg'=>'#F1F9EA','abbr'=>'IBIO'];
        if (str_contains($n, 'manufactura')) return ['accent'=>'#E53935','bg'=>'#FEECEB','abbr'=>'IMA'];
        if (str_contains($n, 'comercio'))    return ['accent'=>'#8E44AD','bg'=>'#F5EEF8','abbr'=>'CIA'];
        if (str_contains($n, 'datos') || str_contains($n, 'artificial')) return ['accent'=>'#00BCD4','bg'=>'#E0F7FA','abbr'=>'IDIA'];
        return ['accent'=>'#03A9F4','bg'=>'#E1F5FE','abbr'=>'TID'];
    };

    $currentSort = request('sort', 'az');
    $currentDept = request('departamento_id');
    $currentCurso = request('curso_id');
    $currentBuscar = request('buscar');
    $hasFilters = $currentDept || $currentCurso || $currentBuscar || $currentSort !== 'az';
    @endphp

    <style>
        .upgp-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .upgp-table thead th { background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left; }
        .upgp-table tbody td { padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9; color:#1e293b; vertical-align:middle; }
        .upgp-table tbody tr:last-child td { border-bottom:none; }
        .upgp-table tbody tr:hover td { background:#F8FAFC; }
        .btn-link { display:inline-flex; align-items:center; gap:4px; color:#1A56B0; padding:0.3rem 0.65rem; border-radius:6px; font-size:0.8rem; font-weight:500; border:1px solid rgba(26,86,176,0.25); text-decoration:none; transition:all 0.15s; }
        .btn-link:hover { background:rgba(26,86,176,0.08); }
        .filter-select, .filter-input { padding:0.45rem 0.75rem; border:1px solid #DDE3EF; border-radius:8px; font-size:0.8rem; color:#1e293b; background:#fff; outline:none; transition:border-color 0.15s; }
        .filter-select:focus, .filter-input:focus { border-color:#1A56B0; box-shadow:0 0 0 3px rgba(26,86,176,0.1); }
        .pagination { display:flex; align-items:center; justify-content:center; gap:0.35rem; padding:1rem 1.5rem; }
        .pagination a, .pagination span { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 0.5rem; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; transition:all 0.15s; }
        .pagination a { color:#1e293b; border:1px solid #E2E8F0; background:#fff; }
        .pagination a:hover { border-color:#1A56B0; color:#1A56B0; background:rgba(26,86,176,0.04); }
        .pagination span.active { background:#1A56B0; color:#fff; border-color:#1A56B0; }
        .pagination span.disabled { color:#94a3b8; border-color:#E2E8F0; background:#F8FAFC; }
    </style>

    <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

        {{-- Header --}}
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Lista General de Alumnos</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">{{ $alumnos->total() }} beneficiario(s) registrados</p>
            </div>
            @if($hasFilters)
                <a href="{{ route('supervisor.alumnos.index') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:0.78rem;color:#64748b;text-decoration:none;padding:0.35rem 0.75rem;border-radius:8px;border:1px solid #E2E8F0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Limpiar filtros
                </a>
            @endif
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('supervisor.alumnos.index') }}" style="padding:1rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem;">
            <div style="flex:1; min-width:180px;">
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.04em;">Buscar</label>
                <input type="text" name="buscar" value="{{ $currentBuscar }}" placeholder="Nombre o usuario…" class="filter-input" style="width:100%;">
            </div>

            <div style="min-width:160px;">
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.04em;">Departamento</label>
                <select name="departamento_id" class="filter-select" style="width:100%;" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($departamentos as $d)
                        <option value="{{ $d->id }}" {{ $currentDept == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width:160px;">
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.04em;">Curso</label>
                <select name="curso_id" class="filter-select" style="width:100%;" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($cursos as $c)
                        <option value="{{ $c->id }}" {{ $currentCurso == $c->id ? 'selected' : '' }}>{{ $c->nombre }} ({{ $c->departamento?->name ?? '—' }})</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width:140px;">
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.04em;">Orden</label>
                <select name="sort" class="filter-select" style="width:100%;" onchange="this.form.submit()">
                    <option value="az" {{ $currentSort === 'az' ? 'selected' : '' }}>A – Z</option>
                    <option value="za" {{ $currentSort === 'za' ? 'selected' : '' }}>Z – A</option>
                </select>
            </div>

            <div style="display:flex; align-items:center; padding-bottom:0;">
                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:0.45rem 1rem;border:none;border-radius:8px;background:#1A56B0;color:#fff;font-size:0.8rem;font-weight:600;cursor:pointer;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Filtrar
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="upgp-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Carrera / Departamento</th>
                        <th>Cursos</th>
                        <th style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnos as $alumno)
                    @php $c = $alumno->department ? $deptColor($alumno->department->name) : null; @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                @if($alumno->avatar_url)
                                    <img src="{{ $alumno->avatar_url }}" alt="Avatar"
                                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                @else
                                    <div style="width:32px; height:32px; border-radius:50%; background:{{ $c ? $c['bg'] : '#F1F5F9' }}; color:{{ $c ? $c['accent'] : '#64748b' }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                        {{ strtoupper(substr($alumno->full_name, 0, 1)) }}
                                    </div>
                                @endif
                                <span style="font-weight:500;">{{ $alumno->full_name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b; font-family:monospace; font-size:0.82rem;">{{ $alumno->username ?? '—' }}</td>
                        <td>
                            @if($alumno->department && $c)
                                <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $c['bg'] }}; color:{{ $c['accent'] }}; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.75rem; font-weight:600; border:1px solid {{ $c['accent'] }}33;">
                                    <span style="font-weight:800; font-size:0.68rem; letter-spacing:0.04em;">{{ $c['abbr'] }}</span>
                                    <span style="color:inherit; opacity:0.8;">{{ $alumno->department->name }}</span>
                                </span>
                            @else
                                <span style="color:#94a3b8; font-size:0.82rem;">Sin departamento</span>
                            @endif
                        </td>
                        <td>
                            @php $count = $alumno->cursos->count(); @endphp
                            @if($count > 0)
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.82rem;color:#475569;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:15px;height:15px;color:#94a3b8;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                                    {{ $count }} curso(s)
                                </span>
                            @else
                                <span style="color:#94a3b8; font-size:0.82rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('supervisor.alumnos.show', $alumno) }}" class="btn-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:3rem; color:#94a3b8;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                            <p style="margin:0;">No se encontraron alumnos con los filtros actuales.</p>
                            @if($hasFilters)
                                <a href="{{ route('supervisor.alumnos.index') }}" style="display:inline-block;margin-top:0.75rem;font-size:0.82rem;color:#1A56B0;">Limpiar filtros</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($alumnos->hasPages())
            <div style="border-top:1px solid #F1F5F9;">
                <div class="pagination">
                    {{-- Previous --}}
                    @if($alumnos->onFirstPage())
                        <span class="disabled">&laquo;</span>
                    @else
                        <a href="{{ $alumnos->previousPageUrl() }}" rel="prev">&laquo;</a>
                    @endif

                    @foreach($alumnos->getUrlRange(max(1, $alumnos->currentPage() - 2), min($alumnos->lastPage(), $alumnos->currentPage() + 2)) as $page => $url)
                        @if($page == $alumnos->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($alumnos->hasMorePages())
                        <a href="{{ $alumnos->nextPageUrl() }}" rel="next">&raquo;</a>
                    @else
                        <span class="disabled">&raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</x-app-layout>