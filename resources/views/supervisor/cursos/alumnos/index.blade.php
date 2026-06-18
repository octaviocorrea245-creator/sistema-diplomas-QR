<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos — {{ $curso->nombre }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <div style="font-size:0.8rem; color:#94a3b8; margin-bottom:1.5rem;">
        <a href="{{ route('supervisor.cursos.index') }}" style="color:#94a3b8; text-decoration:none;">Cursos</a>
        <span style="margin:0 6px;">/</span>
        <a href="{{ route('supervisor.cursos.show', $curso) }}" style="color:#94a3b8; text-decoration:none;">{{ $curso->nombre }}</a>
        <span style="margin:0 6px;">/</span>
        <span style="color:#475569;">Alumnos</span>
    </div>

    {{-- Info del curso --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.25rem 1.5rem; margin-bottom:1.5rem; display:flex; gap:2rem; flex-wrap:wrap;">
        <div>
            <p style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Departamento</p>
            <p style="font-size:0.9rem; font-weight:600; color:#1e293b; margin:0;">{{ $curso->departamento->name ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Horas</p>
            <p style="font-size:0.9rem; font-weight:600; color:#1e293b; margin:0;">{{ $curso->horas }} h</p>
        </div>
        <div>
            <p style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Alumnos inscritos</p>
            <p style="font-size:0.9rem; font-weight:600; color:#1e293b; margin:0;">{{ $alumnos->total() }}</p>
        </div>
    </div>

    {{-- Tabla --}}
    @if($alumnos->isEmpty())
        <div style="text-align:center; padding:4rem 2rem; background:#fff; border-radius:12px; border:1px solid #e2e8f0; color:#94a3b8;">
            <p style="font-size:1rem; margin:0;">Ningún alumno inscrito en este curso.</p>
        </div>
    @else
        <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Alumno</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Departamento</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Estado</th>
                        <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; font-weight:600;">Completado</th>
                        <th style="padding:0.75rem 1.25rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                        @php
                            $estadoMap = [
                                'inscrito'   => ['bg:#DBEAFE','color:#1E40AF'],
                                'en_curso'   => ['bg:#FEF9C3','color:#854D0E'],
                                'completado' => ['bg:#DCFCE7','color:#166534'],
                                'baja'       => ['bg:#FEE2E2','color:#991B1B'],
                            ];
                            $es = $estadoMap[$alumno->pivot->estado] ?? ['bg:#F1F5F9','color:#475569'];
                        @endphp
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:0.85rem 1.25rem;">
                                <span style="font-weight:600; color:#1e293b;">{{ $alumno->full_name ?: $alumno->username }}</span>
                                @if($alumno->full_name && $alumno->full_name !== $alumno->username)
                                    <span style="display:block; font-size:0.75rem; color:#94a3b8;">{{ $alumno->username }}</span>
                                @endif
                            </td>
                            <td style="padding:0.85rem 1.25rem; color:#64748b;">{{ $alumno->department->name ?? '—' }}</td>
                            <td style="padding:0.85rem 1.25rem;">
                                <span style="display:inline-block; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ str_replace('bg:', '', $es[0]) }}; color:{{ str_replace('color:', '', $es[1]) }};">
                                    {{ ucfirst($alumno->pivot->estado) }}
                                </span>
                            </td>
                            <td style="padding:0.85rem 1.25rem; color:#64748b;">
                                {{ $alumno->pivot->fecha_completado
                                    ? \Carbon\Carbon::parse($alumno->pivot->fecha_completado)->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td style="padding:0.85rem 1.25rem; text-align:right;">
                                <a href="{{ route('supervisor.cursos.alumnos.show', [$curso, $alumno]) }}"
                                   style="font-size:0.8rem; color:var(--brand); text-decoration:none; font-weight:500;">
                                    Ver →
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