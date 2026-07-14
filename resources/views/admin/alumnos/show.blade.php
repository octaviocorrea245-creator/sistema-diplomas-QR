<x-app-layout>
    <x-slot name="header">
        <h2>Perfil del Alumno</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; transition:color 0.1s; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .profile-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.5rem; margin-bottom:1.5rem;
            display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
        }
        .avatar {
            width:52px; height:52px; border-radius:50%; background: var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; font-weight:800; color: var(--brand); flex-shrink:0;
        }

        .btn-edit {
            display:inline-flex; align-items:center; gap:6px;
            background:#FFFBEB; color:#D97706; border:1px solid #FDE68A;
            padding:0.45rem 0.9rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-edit:hover { background:#FEF3C7; color:#D97706; }
        .btn-delete {
            display:inline-flex; align-items:center; gap:6px;
            background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;
            padding:0.45rem 0.9rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            cursor:pointer; transition:background 0.1s;
        }
        .btn-delete:hover { background:#FEE2E2; }

        .section-title { font-size:0.82rem; font-weight:700; color:#0D1B35; margin-bottom:0.9rem; text-transform:uppercase; letter-spacing:0.06em; }

        .curso-row {
            background:#fff; border-radius:11px; border:1px solid #E8EDF4;
            padding:1rem 1.25rem; margin-bottom:0.75rem;
            display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
        }
        .curso-nombre { font-size:0.9rem; font-weight:600; color:#1E293B; }
        .curso-meta   { font-size:0.75rem; color:#94A3B8; margin-top:3px; }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-yellow { background:#FFFBEB; color:#D97706; }
        .badge-gray   { background:#F1F5F9; color:#64748B; }

        .empty-state { text-align:center; padding:2.5rem; color:#94A3B8; background:#fff; border-radius:12px; border:1px solid #E8EDF4; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.alumnos.index') }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <span>{{ $alumno->display_name }}</span>
    </div>

    <div class="profile-card">
        <div style="display:flex; align-items:center; gap:1rem;">
            <div class="avatar">{{ strtoupper(substr($alumno->display_name, 0, 1)) }}</div>
            <div>
                <div style="font-size:1.1rem; font-weight:700; color:#1E293B;">{{ $alumno->full_name }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                    {{ $alumno->username ? '@'.$alumno->username : '' }}
                    {{ $alumno->department->name ?? '' }}
                </div>
            </div>
        </div>
        <div style="display:flex; gap:0.6rem; align-items:center;">
            <a href="{{ route('admin.alumnos.edit', $alumno) }}" class="btn-edit">Editar</a>
            <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar a «{{ $alumno->full_name }}»?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="section-title">Cursos inscritos</div>

    @if($alumno->cursos->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:40px;height:40px;display:block;margin:0 auto 0.75rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            <p style="font-size:0.9rem;">Este alumno no tiene cursos inscritos.</p>
        </div>
    @else
        @foreach($alumno->cursos as $curso)
            <div class="curso-row">
                <div>
                    <div class="curso-nombre">{{ $curso->nombre }}</div>
                    <div class="curso-meta">
                        {{ $curso->horas }} horas
                        @if($curso->fecha_inicio)
                            &nbsp;·&nbsp; {{ $curso->fecha_inicio->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') ?? '—' }}
                        @endif
                        @if($curso->pivot->fecha_completado)
                            &nbsp;·&nbsp; Completado el {{ \Carbon\Carbon::parse($curso->pivot->fecha_completado)->format('d/m/Y') }}
                        @endif
                    </div>
                </div>
                @php
                    $badgeClass = match($curso->pivot->estado ?? '') {
                        'completado' => 'badge-green',
                        'inscrito'   => 'badge-yellow',
                        default      => 'badge-gray',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($curso->pivot->estado ?? 'inscrito') }}</span>
            </div>
        @endforeach
    @endif

</x-app-layout>
