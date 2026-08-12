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

        .curso-card { margin-bottom:1rem; }
        .curso-header {
            background:#fff; border:1px solid #E8EDF4; border-radius:14px 14px 0 0;
            padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between;
            border-bottom:none; cursor:pointer; transition:background 0.1s;
        }
        .curso-header:hover { background:#F7F9FC; }
        .curso-nombre { font-size:0.9rem; font-weight:600; color:#1E293B; }
        .curso-meta { font-size:0.75rem; color:#94A3B8; margin-top:2px; }
        .curso-body {
            background:#fff; border:1px solid #E8EDF4; border-radius:0 0 14px 14px;
            padding:0 1.25rem 1rem;
        }
        .curso-body table { width:100%; border-collapse:collapse; font-size:0.82rem; }

        .empty-state { text-align:center; padding:2.5rem; color:#94A3B8; background:#fff; border-radius:12px; border:1px solid #E8EDF4; }
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.alumnos.index') }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <span>{{ $alumno->display_name }}</span>
    </div>

    <div class="page-grid">

        {{-- Panel izquierdo: perfil --}}
        <div>
            <div class="card">
                <div class="card-body">
                    <div style="text-align:center;padding-bottom:1rem;border-bottom:1px solid #F0F4FA;margin-bottom:1rem;">
                        @if($alumno->avatar_url)
                            <img src="{{ $alumno->avatar_url }}" alt="Avatar" class="avatar-lg" style="margin:0 auto 0.75rem;object-fit:cover;">
                        @else
                            <div class="avatar-lg" style="margin:0 auto 0.75rem;">
                                {{ strtoupper(substr($alumno->display_name, 0, 1)) }}
                            </div>
                        @endif
                        <div style="font-size:1rem;font-weight:700;color:#1E293B;">{{ $alumno->display_name }}</div>
                        @if($alumno->username)
                            <div style="font-size:0.78rem;color:#94A3B8;margin-top:2px;">{{ $alumno->username }}</div>
                        @endif
                        @if($alumno->department ?? null)
                            <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">{{ $alumno->department->name }}</div>
                        @endif
                    </div>

                    @if($alumno->email)
                        <div class="info-row">
                            <span class="info-key">Email</span>
                            <span class="info-val">{{ $alumno->email }}</span>
                        </div>
                    @endif
                    <div class="info-row" style="margin-bottom:0;">
                        <span class="info-key">Cursos</span>
                        <span class="info-val">{{ $alumno->cursos->count() }}</span>
                    </div>
                </div>
            </div>

            <div style="margin-top:0.75rem;display:flex;gap:0.6rem;">
                <a href="{{ route('admin.alumnos.edit', $alumno) }}" class="btn-edit" style="flex:1;justify-content:center;">
                    Editar
                </a>
                <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST"
                      onsubmit="return confirmAction(event, '¿Eliminar a «{{ $alumno->full_name }}»?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete">Eliminar</button>
                </form>
            </div>
        </div>

        {{-- Panel derecho: cursos --}}
        <div>
            @forelse($alumno->cursos as $curso)
                @php
                    $estadoActual = $curso->pivot->estado;
                    $estadoBadge = match($estadoActual) {
                        'completado' => 'badge-green',
                        'en_curso'   => 'badge-blue',
                        'inscrito'   => 'badge-orange',
                        'baja'       => 'badge-red',
                        default      => 'badge-gray',
                    };
                    $totalDiplomas = $alumno->diplomas->where('curso_id', $curso->id)->count();
                    $diplomasCurso = $alumno->diplomas->where('curso_id', $curso->id)->sortByDesc('created_at');
                @endphp
                <div class="curso-card">
                    <div class="curso-header" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <div>
                            <div class="curso-nombre">{{ $curso->nombre }}</div>
                            <div class="curso-meta">
                                {{ $curso->horas ?? '—' }}h
                                @if($curso->fecha_inicio)
                                    &middot; {{ $curso->fecha_inicio->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') ?? '—' }}
                                @endif
                                &middot; {{ $totalDiplomas }} diploma(s)
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <span class="badge {{ $estadoBadge }}">{{ ucfirst(str_replace('_',' ',$estadoActual)) }}</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;color:#94A3B8;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="curso-body">
                        <div class="info-row" style="margin-top:1rem;">
                            <span class="info-key">Duración</span>
                            <span class="info-val">{{ $curso->horas ?? '—' }} h</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Inicio</span>
                            <span class="info-val">{{ $curso->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        <div class="info-row" style="margin-bottom:0;">
                            <span class="info-key">Fin</span>
                            <span class="info-val">{{ $curso->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                        </div>

                        @if($diplomasCurso->isNotEmpty())
                            <table style="margin-top:1rem;width:100%;border-collapse:collapse;font-size:0.82rem;">
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
                        @else
                            <div style="margin-top:1rem;font-size:0.8rem;color:#94A3B8;text-align:center;padding:0.75rem;">
                                Sin diplomas emitidos
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p style="font-size:0.9rem;">Este alumno no tiene cursos inscritos.</p>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        document.querySelectorAll('.curso-body').forEach(function(el) { el.classList.add('hidden'); });
    </script>

</x-app-layout>