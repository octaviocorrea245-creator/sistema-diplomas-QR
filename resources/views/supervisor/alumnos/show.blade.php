<x-app-layout>
    <x-slot name="header">
        <h2>{{ $alumno->full_name ?: $alumno->username }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <div style="font-size:0.8rem; color:#94a3b8; margin-bottom:1.5rem;">
        <a href="{{ route('supervisor.alumnos.index') }}" style="color:#94a3b8; text-decoration:none;">Alumnos</a>
        <span style="margin:0 6px;">/</span>
        <span style="color:#475569;">{{ $alumno->full_name ?: $alumno->username }}</span>
    </div>

    {{-- Perfil --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1.25rem;">
        <div style="width:52px; height:52px; border-radius:50%; background:var(--brand-bg); display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:700; color:var(--brand); flex-shrink:0;">
            {{ strtoupper(substr($alumno->full_name ?: $alumno->username, 0, 1)) }}
        </div>
        <div>
            <p style="font-size:1.05rem; font-weight:700; color:#1e293b; margin:0;">{{ $alumno->full_name ?: $alumno->username }}</p>
            @if($alumno->full_name && $alumno->full_name !== $alumno->username)
                <p style="font-size:0.8rem; color:#94a3b8; margin:2px 0 0;">@{{ $alumno->username }}</p>
            @endif
            <p style="font-size:0.82rem; color:#64748b; margin:3px 0 0;">{{ $alumno->department->name ?? '—' }}</p>
        </div>
    </div>

    {{-- Cursos agrupados por departamento --}}
    <p style="font-size:0.875rem; font-weight:600; color:#475569; margin:0 0 1rem;">
        Cursos inscritos
        <span style="font-weight:400; color:#94a3b8;">({{ $alumno->cursos->count() }})</span>
    </p>

    @if($alumno->cursos->isEmpty())
        <div style="text-align:center; padding:3rem 2rem; background:#fff; border-radius:12px; border:1px solid #e2e8f0; color:#94a3b8;">
            <p style="margin:0;">Este alumno no tiene cursos inscritos.</p>
        </div>
    @else
        @foreach($alumno->cursos->groupBy(fn($c) => $c->departamento->name ?? 'Sin departamento') as $depto => $cursos)
            <div style="margin-bottom:1.5rem;">
                <p style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:600; margin:0 0 0.6rem;">
                    {{ $depto }}
                </p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($cursos as $curso)
                        @php
                            $estadoMap = [
                                'inscrito'   => ['bg:#DBEAFE','color:#1E40AF'],
                                'en_curso'   => ['bg:#FEF9C3','color:#854D0E'],
                                'completado' => ['bg:#DCFCE7','color:#166534'],
                                'baja'       => ['bg:#FEE2E2','color:#991B1B'],
                            ];
                            $es = $estadoMap[$curso->pivot->estado] ?? ['bg:#F1F5F9','color:#475569'];
                        @endphp
                        <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                            <div>
                                <p style="font-weight:600; color:#1e293b; margin:0 0 2px;">{{ $curso->nombre }}</p>
                                <p style="font-size:0.78rem; color:#94a3b8; margin:0;">
                                    {{ $curso->horas }} horas
                                    · {{ $curso->fecha_inicio?->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') }}
                                </p>
                                @if($curso->pivot->fecha_completado)
                                    <p style="font-size:0.78rem; color:#16a34a; margin:2px 0 0;">
                                        Completado el {{ \Carbon\Carbon::parse($curso->pivot->fecha_completado)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <span style="display:inline-block; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ str_replace('bg:', '', $es[0]) }}; color:{{ str_replace('color:', '', $es[1]) }};">
                                    {{ ucfirst($curso->pivot->estado) }}
                                </span>
                                <a href="{{ route('supervisor.cursos.alumnos.show', [$curso, $alumno]) }}"
                                   style="font-size:0.8rem; color:var(--brand); text-decoration:none; font-weight:500; white-space:nowrap;">
                                    Ver →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

</x-app-layout>