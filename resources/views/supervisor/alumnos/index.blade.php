<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos</h2>
    </x-slot>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('supervisor.alumnos.index') }}"
          style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1.5rem;">

        <input type="text" name="buscar" value="{{ request('buscar') }}"
               placeholder="Buscar por nombre o username…"
               style="flex:1; min-width:200px; border:1px solid #e2e8f0; border-radius:8px; padding:0.5rem 0.85rem; font-size:0.875rem; color:#1e293b; outline:none;">

        <select name="departamento_id"
                style="border:1px solid #e2e8f0; border-radius:8px; padding:0.5rem 0.85rem; font-size:0.875rem; color:#1e293b; outline:none; background:#fff;">
            <option value="">Todos los departamentos</option>
            @foreach($departamentos as $dep)
                <option value="{{ $dep->id }}" {{ request('departamento_id') == $dep->id ? 'selected' : '' }}>
                    {{ $dep->name }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                style="background:var(--brand); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.875rem; font-weight:500; cursor:pointer;">
            Filtrar
        </button>

        @if(request('buscar') || request('departamento_id'))
            <a href="{{ route('supervisor.alumnos.index') }}"
               style="border:1px solid #e2e8f0; border-radius:8px; padding:0.5rem 1rem; font-size:0.875rem; color:#64748b; text-decoration:none; display:flex; align-items:center;">
                Limpiar
            </a>
        @endif
    </form>

    {{-- Tabla --}}
    @if($alumnos->isEmpty())
        <div style="text-align:center; padding:4rem 2rem; background:#fff; border-radius:12px; border:1px solid #e2e8f0; color:#94a3b8;">
            <p style="font-size:1rem; margin:0;">No se encontraron alumnos.</p>
            @if(request('buscar') || request('departamento_id'))
                <p style="font-size:0.82rem; margin-top:6px;">Intenta con otros filtros.</p>
            @endif
        </div>
    @else
        <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Nombre</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Departamento</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Cursos</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Completados</th>
                        <th style="padding:0.75rem 1.25rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                        @php
                            $total       = $alumno->cursos->count();
                            $completados = $alumno->cursos->where('pivot.estado', 'completado')->count();
                        @endphp
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:0.85rem 1.25rem;">
                                <span style="font-weight:600; color:#1e293b;">{{ $alumno->full_name ?: $alumno->username }}</span>
                                @if($alumno->full_name && $alumno->full_name !== $alumno->username)
                                    <span style="display:block; font-size:0.75rem; color:#94a3b8;">{{ $alumno->username }}</span>
                                @endif
                            </td>
                            <td style="padding:0.85rem 1.25rem; color:#64748b;">{{ $alumno->department->name ?? '—' }}</td>
                            <td style="padding:0.85rem 1.25rem; color:#64748b;">{{ $total }}</td>
                            <td style="padding:0.85rem 1.25rem;">
                                <span style="color:{{ $completados > 0 ? '#16a34a' : '#94a3b8' }}; font-weight:{{ $completados > 0 ? '600' : '400' }};">
                                    {{ $completados }}
                                </span>
                            </td>
                            <td style="padding:0.85rem 1.25rem; text-align:right;">
                                <a href="{{ route('supervisor.alumnos.show', $alumno) }}"
                                   style="font-size:0.8rem; color:var(--brand); text-decoration:none; font-weight:500;">
                                    Ver detalle →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $alumnos->links() }}
        </div>
    @endif

</x-app-layout>