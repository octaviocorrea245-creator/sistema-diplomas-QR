{{-- resources/views/admin/cursos/alumnos/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>{{ $alumno->display_name }}</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:280px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:800px){ .page-grid { grid-template-columns:1fr; } }

        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding:1.5rem; }
        .card-title { font-size:0.7rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.6rem; border-bottom:1px solid #F0F4FA; }

        .avatar-lg {
            width:64px; height:64px; border-radius:50%; background:var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; font-weight:700; color:var(--brand); flex-shrink:0;
        }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-blue   { background:#EFF6FF; color:#1D4ED8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }

        .info-row { display:flex; gap:8px; margin-bottom:0.75rem; font-size:0.875rem; align-items:flex-start; }
        .info-key { color:#64748b; min-width:130px; flex-shrink:0; font-size:0.82rem; }
        .info-val { color:#1E293B; font-weight:500; }

        .btn-outline {
            display:inline-flex; align-items:center; gap:6px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.82rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-outline:hover { background:#F7F9FC; color:#475569; }

        .stat-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-top:1rem; }
        .stat-box { background:#F7F9FC; border-radius:10px; padding:0.85rem 1rem; }
        .stat-label { font-size:0.68rem; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:0.07em; }
        .stat-value { font-size:1.1rem; font-weight:700; color:#1E293B; margin-top:2px; }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}">{{ $curso->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <span>{{ $alumno->display_name }}</span>
    </div>

    <div class="page-grid">

        {{-- Panel izquierdo: perfil --}}
        <div>
            <div class="card">
                <div class="card-body">
                    <div style="text-align:center;padding-bottom:1rem;border-bottom:1px solid #F0F4FA;margin-bottom:1rem;">
                        <div class="avatar-lg" style="margin:0 auto 0.75rem;">
                            {{ strtoupper(substr($alumno->display_name, 0, 1)) }}
                        </div>
                        <div style="font-size:1rem;font-weight:700;color:#1E293B;">{{ $alumno->display_name }}</div>
                        @if($alumno->username)
                            <div style="font-size:0.78rem;color:#94A3B8;margin-top:2px;">{{ $alumno->username }}</div>
                        @endif
                        @if($alumno->department ?? null)
                            <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">{{ $alumno->department->name }}</div>
                        @endif
                    </div>

                    @php
                        $estadoActual = $inscripcion->pivot->estado;
                        $estadoBadge = match($estadoActual) {
                            'completado' => 'badge-green',
                            'en_curso'   => 'badge-blue',
                            'inscrito'   => 'badge-orange',
                            'baja'       => 'badge-red',
                            default      => 'badge-gray',
                        };
                    @endphp

                    <div class="info-row">
                        <span class="info-key">Estado</span>
                        <span class="badge {{ $estadoBadge }}">{{ ucfirst(str_replace('_',' ',$estadoActual)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Inscrito</span>
                        <span class="info-val">{{ $inscripcion->pivot->created_at?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="info-row" style="margin-bottom:0;">
                        <span class="info-key">Completado</span>
                        <span class="info-val">
                            {{ $inscripcion->pivot->fecha_completado
                                ? \Carbon\Carbon::parse($inscripcion->pivot->fecha_completado)->format('d/m/Y')
                                : '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top:0.75rem;">
                <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}" class="btn-outline" style="width:100%;justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                    </svg>
                    Editar inscripción
                </a>
            </div>
        </div>

        {{-- Panel derecho: curso --}}
        <div>
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Curso</p>

                    <div class="info-row">
                        <span class="info-key">Nombre</span>
                        <span class="info-val">{{ $curso->nombre }}</span>
                    </div>
                    @if($curso->descripcion)
                        <div class="info-row">
                            <span class="info-key">Descripción</span>
                            <span class="info-val" style="color:#475569;font-weight:400;font-size:0.82rem;">{{ $curso->descripcion }}</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="info-key">Inicio</span>
                        <span class="info-val">{{ $curso->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Fin</span>
                        <span class="info-val">{{ $curso->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                    </div>

                    <div class="stat-grid">
                        <div class="stat-box">
                            <div class="stat-label">Duración</div>
                            <div class="stat-value">{{ $curso->horas ?? '—' }}<span style="font-size:0.7rem;font-weight:500;color:#64748b;"> h</span></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Diplomas</div>
                            <div class="stat-value">
                                @php
                                    $totalDiplomas = $alumno->diplomas()
                                        ->where('curso_id', $curso->id)
                                        ->count();
                                @endphp
                                {{ $totalDiplomas }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Diplomas del alumno en este curso --}}
            @php
                $diplomasCurso = $alumno->diplomas()
                    ->where('curso_id', $curso->id)
                    ->orderByDesc('created_at')
                    ->get();
            @endphp

            @if($diplomasCurso->isNotEmpty())
                <div class="card" style="margin-top:1rem;">
                    <div class="card-body" style="padding-bottom:0;">
                        <p class="card-title">Diplomas emitidos</p>
                    </div>
                    <table style="width:100%;border-collapse:collapse;font-size:0.82rem;">
                        <thead>
                            <tr>
                                <th style="background:#F7F9FC;border-bottom:1px solid #E8EDF4;padding:0.6rem 1rem;text-align:left;font-size:0.7rem;font-weight:600;color:#4A6585;letter-spacing:0.07em;text-transform:uppercase;">Folio</th>
                                <th style="background:#F7F9FC;border-bottom:1px solid #E8EDF4;padding:0.6rem 1rem;text-align:left;font-size:0.7rem;font-weight:600;color:#4A6585;letter-spacing:0.07em;text-transform:uppercase;">Estado</th>
                                <th style="background:#F7F9FC;border-bottom:1px solid #E8EDF4;padding:0.6rem 1rem;text-align:left;font-size:0.7rem;font-weight:600;color:#4A6585;letter-spacing:0.07em;text-transform:uppercase;">Emisión</th>
                                <th style="background:#F7F9FC;border-bottom:1px solid #E8EDF4;padding:0.6rem 1rem;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diplomasCurso as $d)
                                @php
                                    $dBadge = match($d->estado) {
                                        'emitido'   => 'badge-green',
                                        'reemitido' => 'badge-blue',
                                        'revocado'  => 'badge-red',
                                        default     => 'badge-gray',
                                    };
                                @endphp
                                <tr style="border-bottom:1px solid #F0F4FA;">
                                    <td style="padding:0.65rem 1rem;font-family:monospace;font-size:0.78rem;color:#334155;">{{ $d->folio }}</td>
                                    <td style="padding:0.65rem 1rem;"><span class="badge {{ $dBadge }}">{{ ucfirst($d->estado) }}</span></td>
                                    <td style="padding:0.65rem 1rem;color:#64748b;">{{ $d->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                    <td style="padding:0.65rem 1rem;text-align:right;">
                                        <a href="{{ route('admin.diplomas.show', $d) }}"
                                           style="font-size:0.75rem;color:var(--brand);font-weight:600;text-decoration:none;">
                                            Ver →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
