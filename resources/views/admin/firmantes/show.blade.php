<x-app-layout>
    <x-slot name="header">
        <h2>{{ $firmante->nombre }}</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .grid-2 { display:grid; grid-template-columns:340px 1fr; gap:1.5rem; align-items:start; }
        @media(max-width:900px) { .grid-2 { grid-template-columns:1fr; } }

        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding:1.5rem; }
        .card-title { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:1rem; padding-bottom:0.6rem; border-bottom:1px solid #F0F4FA; }

        .info-row { display:flex; gap:8px; margin-bottom:0.75rem; font-size:0.875rem; }
        .info-key { color:#64748b; min-width:130px; flex-shrink:0; }
        .info-val { color:#1E293B; font-weight:500; word-break:break-all; }
        .mono { font-family:monospace; font-size:0.8rem; }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }

        .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .data-table thead th {
            background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.65rem 1rem; text-align:left;
            font-size:0.7rem; font-weight:600; color:#4A6585;
            letter-spacing:0.07em; text-transform:uppercase;
        }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.7rem 1rem; color:#334155; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:6px;
            background:var(--brand); color:#fff; border:none;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-outline {
            display:inline-flex; align-items:center; gap:6px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:background 0.1s;
        }
        .btn-outline:hover { background:#F7F9FC; color:#475569; }

        .empty-state { text-align:center; padding:2.5rem 1rem; color:#94A3B8; }

        .alert-box { border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; }
    </style>

    @if(session('toast'))
        @php $t = session('toast'); @endphp
        <div class="alert-box {{ $t['type'] === 'success' ? 'alert-success' : 'alert-error' }}">
            {{ $t['message'] }}
        </div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.firmantes.index') }}">Firmantes</a>
        <span class="breadcrumb-sep">/</span>
        <span>{{ $firmante->nombre }}</span>
    </div>

    <div class="grid-2">

        {{-- Panel izquierdo: datos del firmante --}}
        <div>
            <div class="card" style="margin-bottom:1rem;">
                <div class="card-body">
                    {{-- Avatar --}}
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:1.25rem;">
                        <div style="width:52px;height:52px;border-radius:50%;background:var(--brand-bg);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:1.2rem;font-weight:700;color:var(--brand);flex-shrink:0;">
                            {{ strtoupper(substr($firmante->nombre,0,1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1E293B;font-size:1rem;">{{ $firmante->nombre }}</div>
                            <div style="font-size:0.8rem;color:#64748b;">{{ $firmante->cargo }}</div>
                        </div>
                    </div>

                    @php $vigente = $firmante->certVigente(); $dias = $firmante->diasParaExpirar(); @endphp

                    <div class="info-row">
                        <span class="info-key">Estado</span>
                        <span>
                            @if(!$firmante->activo)
                                <span class="badge badge-gray">Inactivo</span>
                            @elseif(!$vigente)
                                <span class="badge badge-red">Cert. inválido</span>
                            @else
                                <span class="badge badge-green">Activo</span>
                            @endif
                        </span>
                    </div>

                    <p class="card-title" style="margin-top:1rem;">Certificado e.firma</p>

                    @if($firmante->rfc)
                        <div class="info-row">
                            <span class="info-key">RFC</span>
                            <span class="info-val mono">{{ $firmante->rfc }}</span>
                        </div>
                    @endif
                    @if($firmante->certificado_numero)
                        <div class="info-row">
                            <span class="info-key">No. serie</span>
                            <span class="info-val mono" style="font-size:0.72rem;">{{ $firmante->certificado_numero }}</span>
                        </div>
                    @endif
                    @if($firmante->cert_valido_desde)
                        <div class="info-row">
                            <span class="info-key">Válido desde</span>
                            <span class="info-val">{{ $firmante->cert_valido_desde->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    @if($firmante->cert_expira_en)
                        <div class="info-row">
                            <span class="info-key">Vence</span>
                            <span class="info-val" style="color:{{ $vigente ? '#15803D' : '#DC2626' }};">
                                {{ $firmante->cert_expira_en->format('d/m/Y') }}
                                @if($dias !== null && $dias >= 0 && $dias <= 30)
                                    <span class="badge badge-orange" style="margin-left:4px;">{{ $dias }}d</span>
                                @elseif(!$vigente)
                                    <span class="badge badge-red" style="margin-left:4px;">Vencido</span>
                                @endif
                            </span>
                        </div>
                    @endif

                    <div style="display:flex;gap:8px;margin-top:1.5rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.firmantes.edit', $firmante) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/>
                            </svg>
                            Editar
                        </a>
                        <form action="{{ route('admin.firmantes.toggle-activo', $firmante) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-outline">
                                {{ $firmante->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Firma masiva --}}
            @if($firmante->activo && $vigente)
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Firma masiva de curso</p>
                        <p style="font-size:0.8rem;color:#64748b;margin-bottom:1rem;">
                            Firma todos los diplomas pendientes de un curso con un solo paso.
                        </p>
                        <form method="POST" action="{{ route('admin.firmantes.firmar-masivo', $firmante) }}">
                            @csrf
                            <div style="margin-bottom:0.75rem;">
                                <select name="curso_id" required
                                        style="width:100%;border:1px solid #DDE3EF;border-radius:8px;padding:0.55rem 0.8rem;font-size:0.82rem;outline:none;">
                                    <option value="">— Selecciona un curso —</option>
                                    @foreach(\App\Models\Cursos::where('departamento_id', auth()->user()->department_id)->orderBy('nombre')->get() as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-bottom:0.75rem;">
                                <input type="password" name="password" placeholder="Contraseña de e.firma" required
                                       style="width:100%;border:1px solid #DDE3EF;border-radius:8px;padding:0.55rem 0.8rem;font-size:0.82rem;outline:none;box-sizing:border-box;">
                            </div>
                            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;"
                                    onclick="return confirmAction(event, '¿Firmar todos los diplomas pendientes del curso seleccionado?')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                                Firmar diplomas pendientes
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Panel derecho: diplomas firmados --}}
        <div class="card">
            <div class="card-body" style="padding-bottom:0;">
                <p class="card-title">Diplomas firmados por {{ $firmante->nombre }}</p>
            </div>
            @if($diplomas->isEmpty())
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:40px;height:40px;margin:0 auto 0.75rem;display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p style="font-size:0.875rem;">Aún no ha firmado ningún diploma.</p>
                </div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th>Folio</th>
                            <th>Firmado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diplomas as $d)
                            <tr>
                                <td style="font-weight:500;">{{ $d->alumno->full_name ?? '—' }}</td>
                                <td style="font-size:0.82rem;color:#64748b;">{{ $d->curso->nombre ?? '—' }}</td>
                                <td style="font-family:monospace;font-size:0.8rem;color:#475569;">{{ $d->folio }}</td>
                                <td style="font-size:0.8rem;color:#64748b;">
                                    {{ $d->firmado_en?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:1rem;">{{ $diplomas->links() }}</div>
            @endif
        </div>

    </div>

    {{-- ─── Auditoría de firmas ─── --}}
    <div class="card" style="margin-top:1.5rem;">
        <div class="card-body" style="padding-bottom:0;">
            <p class="card-title" style="display:flex;align-items:center;gap:8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
                Auditoría — últimas 50 operaciones
            </p>
        </div>

        @if($auditorias->isEmpty())
            <div class="empty-state">
                <p style="font-size:0.875rem;">Aún no hay registros de auditoría para este firmante.</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Diploma (Folio)</th>
                        <th>Alumno</th>
                        <th>Tipo</th>
                        <th>Usuario</th>
                        <th>No. Certificado</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditorias as $a)
                        <tr>
                            <td style="font-size:0.8rem;white-space:nowrap;color:#475569;">
                                {{ $a->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td style="font-family:monospace;font-size:0.78rem;color:#475569;">
                                {{ $a->diploma->folio ?? '—' }}
                            </td>
                            <td style="font-size:0.82rem;">
                                {{ $a->diploma->alumno->full_name ?? '—' }}
                            </td>
                            <td>
                                @if($a->accion === 'masivo')
                                    <span class="badge badge-orange">Masivo</span>
                                @else
                                    <span class="badge" style="background:#EEF2FF;color:#4338CA;">Individual</span>
                                @endif
                            </td>
                            <td style="font-size:0.82rem;color:#64748b;">
                                {{ $a->usuario->full_name ?? $a->usuario->name ?? '—' }}
                            </td>
                            <td style="font-family:monospace;font-size:0.72rem;color:#64748b;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                title="{{ $a->cert_serie }}">
                                {{ $a->cert_serie ? substr($a->cert_serie, 0, 20) . (strlen($a->cert_serie) > 20 ? '…' : '') : '—' }}
                            </td>
                            <td style="font-size:0.78rem;color:#94A3B8;">{{ $a->ip ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</x-app-layout>
