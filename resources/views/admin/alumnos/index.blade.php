<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos</h2>
    </x-slot>

    <style>
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }

        .data-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
        }
        .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .data-table thead th {
            background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.7rem 1.1rem; text-align:left;
            font-size:0.72rem; font-weight:600; color:#4A6585;
            letter-spacing:0.07em; text-transform:uppercase;
        }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.75rem 1.1rem; color:#334155; }

        .badge {
            display:inline-flex; align-items:center; padding:0.2rem 0.65rem;
            border-radius:20px; font-size:0.72rem; font-weight:600;
        }
        .badge-green { background:#F0FDF4; color:#16A34A; }
        .badge-gray  { background:#F1F5F9; color:#94A3B8; }

        .search-row { display:flex; gap:8px; margin-bottom:1.5rem; }
        .search-input {
            flex:1; border:1px solid #DDE3EF; border-radius:9px; padding:0.55rem 1rem;
            font-size:0.875rem; outline:none; transition:border 0.15s;
        }
        .search-input:focus { border-color: var(--brand); }

        .action-link { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view   { color: var(--brand); }
        .link-edit   { color:#D97706; }
        .link-delete { color:#DC2626; background:none; border:none; cursor:pointer; font-size:0.8rem; font-weight:500; padding:0; }
        .link-delete:hover { opacity:0.7; }

        .empty-state { text-align:center; padding:3rem 1rem; color:#94A3B8; }
        .empty-state svg { margin:0 auto 1rem; display:block; }

        .alert-success {
            background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1rem;
        }
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
            <strong>Filas con errores:</strong>
            <ul style="margin:0.3rem 0 0 1.1rem;padding:0;">
                @foreach(session('import_errores') as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
    @if(session('import_omitidos') && count(session('import_omitidos')))
        <div class="alert-warning">
            <strong>Omitidos (ya existían):</strong> {{ implode(', ', session('import_omitidos')) }}
        </div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <div>
            <p style="font-size:0.8rem; color:#64748b; margin:0.2rem 0 0;">{{ auth()->user()->department->name ?? 'Tu departamento' }}</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <a href="{{ route('admin.alumnos.importar') }}" class="btn-outline-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Importar CSV
            </a>
            <a href="{{ route('admin.alumnos.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nuevo Alumno
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.alumnos.index') }}">
        <div class="search-row">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   class="search-input" placeholder="Buscar por nombre…">
            <button type="submit" class="btn-primary">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('admin.alumnos.index') }}"
                   style="display:inline-flex; align-items:center; padding:0.55rem 1rem; border-radius:9px; border:1px solid #DDE3EF; font-size:0.83rem; color:#64748b; text-decoration:none; transition:background 0.1s;"
                   onmouseover="this.style.background='#F7F9FC'" onmouseout="this.style.background='transparent'">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    @if($alumnos->isEmpty())
        <div class="data-card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:48px;height:48px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                </svg>
                @if(request('buscar'))
                    <p style="font-size:0.95rem; margin-bottom:0.25rem;">Sin resultados para "{{ request('buscar') }}"</p>
                    <p style="font-size:0.82rem;">Intenta con otro término.</p>
                @else
                    <p style="font-size:0.95rem; margin-bottom:0.25rem;">No hay alumnos registrados</p>
                    <p style="font-size:0.82rem;">Agrega el primero con el botón de arriba.</p>
                @endif
            </div>
        </div>
    @else
        <div class="data-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Cursos inscritos</th>
                        <th>Completados</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                        @php
                            $total       = $alumno->cursos->count();
                            $completados = $alumno->cursos->where('pivot.estado', 'completado')->count();
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:34px; height:34px; border-radius:50%; background: var(--brand-bg);
                                                display:flex; align-items:center; justify-content:center;
                                                font-size:0.8rem; font-weight:700; color: var(--brand); flex-shrink:0;">
                                        {{ strtoupper(substr($alumno->full_name, 0, 1)) }}
                                    </div>
                                    <span style="font-weight:500; color:#1E293B;">{{ $alumno->full_name }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b; font-size:0.82rem;">{{ $alumno->username ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $total > 0 ? 'badge-green' : 'badge-gray' }}">{{ $total }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $completados > 0 ? 'badge-green' : 'badge-gray' }}">{{ $completados }}</span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:1rem; justify-content:flex-end; align-items:center;">
                                    <a href="{{ route('admin.alumnos.show', $alumno) }}" class="action-link link-view">Ver</a>
                                    <a href="{{ route('admin.alumnos.edit', $alumno) }}" class="action-link link-edit">Editar</a>
                                    <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar a «{{ $alumno->full_name }}»?')" style="margin:0;">
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
        <div style="margin-top:1rem;">{{ $alumnos->links() }}</div>
    @endif

</x-app-layout>
