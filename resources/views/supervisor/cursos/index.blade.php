<x-app-layout>
    <x-slot name="header">
        <h2>Cursos</h2>
    </x-slot>

    <style>
        .upgp-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .upgp-table thead th { background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left; }
        .upgp-table tbody td { padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9; color:#1e293b; vertical-align:middle; }
        .upgp-table tbody tr:last-child td { border-bottom:none; }
        .upgp-table tbody tr:hover td { background:#F8FAFC; }
        .btn-link { display:inline-flex; align-items:center; gap:4px; color:#1A56B0; padding:0.3rem 0.65rem; border-radius:6px; font-size:0.8rem; font-weight:500; border:1px solid rgba(26,86,176,0.25); text-decoration:none; transition:all 0.15s; }
        .btn-link:hover { background:rgba(26,86,176,0.08); }
        .btn-link-amber { color:#D97706; border-color:rgba(217,119,6,0.25); }
        .btn-link-amber:hover { background:rgba(217,119,6,0.08); }
    </style>

    <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9;">
            <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Lista General de Cursos</h3>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">Vista de supervisión de todos los cursos del sistema</p>
        </div>

        <form method="GET" style="display:flex; gap:0.75rem; align-items:end; padding:1rem 1.5rem; border-bottom:1px solid #F1F5F9; flex-wrap:wrap;">
            <div>
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:0.25rem; text-transform:uppercase;">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del curso..."
                       style="padding:0.45rem 0.75rem; border:1px solid #DDE3EF; border-radius:8px; font-size:0.82rem; width:220px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:0.25rem; text-transform:uppercase;">Departamento</label>
                <select name="departamento_id" style="padding:0.45rem 0.75rem; border:1px solid #DDE3EF; border-radius:8px; font-size:0.82rem; outline:none;">
                    <option value="">Todos</option>
                    @foreach($departamentos as $d)
                        <option value="{{ $d->id }}" {{ request('departamento_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:0.7rem; font-weight:600; color:#64748b; margin-bottom:0.25rem; text-transform:uppercase;">Estado</label>
                <select name="estado" style="padding:0.45rem 0.75rem; border:1px solid #DDE3EF; border-radius:8px; font-size:0.82rem; outline:none;">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="borrador" {{ request('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit"
                        style="padding:0.45rem 1rem; background:#1A56B0; color:#fff; border:none; border-radius:8px; font-size:0.82rem; font-weight:500; cursor:pointer;">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search','departamento_id','estado']))
                    <a href="{{ route('supervisor.cursos.index') }}"
                       style="padding:0.45rem 1rem; background:#fff; color:#64748b; border:1px solid #DDE3EF; border-radius:8px; font-size:0.82rem; font-weight:500; text-decoration:none;">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>

        <div style="overflow-x:auto; padding-bottom:0.5rem;">
            <table class="upgp-table">
                <thead>
                    <tr>
                        <th>Nombre del Curso</th>
                        <th>Departamento</th>
                        <th style="width:80px;">Horas</th>
                        <th style="width:110px;">Estado</th>
                        <th style="width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cursos as $curso)
                    <tr>
                        <td style="font-weight:500;">{{ $curso->nombre }}</td>
                        <td style="color:#64748b; font-size:0.82rem;">{{ $curso->departamento->name ?? '—' }}</td>
                        <td style="text-align:center; color:#64748b;">{{ $curso->horas ?? '—' }}</td>
                        <td>
                            @php
                                $estadoMap = [
                                    'activo'     => ['bg:#DCFCE7','color:#166534'],
                                    'borrador'   => ['bg:#F1F5F9','color:#475569'],
                                    'finalizado' => ['bg:#EEF3FB','color:#1A56B0'],
                                    'cancelado'  => ['bg:#FEE2E2','color:#991B1B'],
                                ];
                                $es = $estadoMap[$curso->estado] ?? ['bg:#F1F5F9','color:#475569'];
                            @endphp
                            <span style="display:inline-block; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ explode(',', implode(',', $es))[0] }};
                                   background: {{ str_replace('bg:', '', $es[0]) }}; color: {{ str_replace('color:', '', $es[1]) }};">
                                {{ ucfirst($curso->estado) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <a href="{{ route('supervisor.cursos.show', $curso) }}" class="btn-link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Detalle
                                </a>
                                <a href="{{ route('supervisor.cursos.alumnos.index', $curso) }}" class="btn-link btn-link-amber">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                                    Alumnos
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:3rem; color:#94a3b8;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                            No hay cursos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
