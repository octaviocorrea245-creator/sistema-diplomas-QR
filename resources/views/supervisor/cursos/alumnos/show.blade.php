<x-app-layout>
    <x-slot name="header">
        <h2>{{ $alumno->full_name ?: $alumno->username }} — {{ $curso->nombre }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <div style="font-size:0.8rem; color:#94a3b8; margin-bottom:1.5rem;">
        <a href="{{ route('supervisor.cursos.index') }}" style="color:#94a3b8; text-decoration:none;">Cursos</a>
        <span style="margin:0 6px;">/</span>
        <a href="{{ route('supervisor.cursos.alumnos.index', $curso) }}" style="color:#94a3b8; text-decoration:none;">Alumnos</a>
        <span style="margin:0 6px;">/</span>
        <span style="color:#475569;">{{ $alumno->full_name ?: $alumno->username }}</span>
    </div>

    {{-- Perfil del alumno --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:1.25rem;">
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

    {{-- Datos de inscripción --}}
    @php
        $estadoMap = [
            'inscrito'   => ['bg:#DBEAFE','color:#1E40AF'],
            'en_curso'   => ['bg:#FEF9C3','color:#854D0E'],
            'completado' => ['bg:#DCFCE7','color:#166534'],
            'baja'       => ['bg:#FEE2E2','color:#991B1B'],
        ];
        $es = $estadoMap[$inscripcion->pivot->estado] ?? ['bg:#F1F5F9','color:#475569'];
    @endphp

    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.25rem;">
        <p style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; font-weight:600; margin:0 0 1.25rem;">Inscripción en {{ $curso->nombre }}</p>

        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:1.25rem;">
            <div>
                <p style="font-size:0.72rem; color:#94a3b8; margin:0 0 4px;">Estado</p>
                <span style="display:inline-block; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.78rem; font-weight:600; background:{{ str_replace('bg:', '', $es[0]) }}; color:{{ str_replace('color:', '', $es[1]) }};">
                    {{ ucfirst($inscripcion->pivot->estado) }}
                </span>
            </div>
            <div>
                <p style="font-size:0.72rem; color:#94a3b8; margin:0 0 4px;">Inscrito el</p>
                <p style="font-size:0.9rem; color:#1e293b; font-weight:500; margin:0;">
                    {{ $inscripcion->pivot->created_at?->format('d/m/Y') ?? '—' }}
                </p>
            </div>
            <div>
                <p style="font-size:0.72rem; color:#94a3b8; margin:0 0 4px;">Fecha de completado</p>
                <p style="font-size:0.9rem; color:#1e293b; font-weight:500; margin:0;">
                    {{ $inscripcion->pivot->fecha_completado
                        ? \Carbon\Carbon::parse($inscripcion->pivot->fecha_completado)->format('d/m/Y')
                        : '—' }}
                </p>
            </div>
            <div>
                <p style="font-size:0.72rem; color:#94a3b8; margin:0 0 4px;">Horas del curso</p>
                <p style="font-size:0.9rem; color:#1e293b; font-weight:500; margin:0;">{{ $curso->horas }} h</p>
            </div>
            <div>
                <p style="font-size:0.72rem; color:#94a3b8; margin:0 0 4px;">Periodo</p>
                <p style="font-size:0.9rem; color:#1e293b; font-weight:500; margin:0;">
                    {{ $curso->fecha_inicio?->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>

    <a href="{{ route('supervisor.alumnos.show', $alumno) }}"
       style="font-size:0.82rem; color:var(--brand); text-decoration:none; font-weight:500;">
        ← Ver todos los cursos de este alumno
    </a>

</x-app-layout>