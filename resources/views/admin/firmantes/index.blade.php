<x-app-layout>
    <x-slot name="header">
        <h2>Firmantes (e.firma)</h2>
    </x-slot>

    <style>
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }

        .data-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
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

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }

        .action-link  { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view   { color:var(--brand); }
        .link-edit   { color:#D97706; }
        .link-delete { color:#DC2626; background:none; border:none; cursor:pointer; font-size:0.8rem; font-weight:500; padding:0; }
        .link-delete:hover { opacity:0.7; }
        .link-toggle { color:#0891B2; background:none; border:none; cursor:pointer; font-size:0.8rem; font-weight:500; padding:0; }
        .link-toggle:hover { opacity:0.7; }

        .empty-state { text-align:center; padding:3rem 1rem; color:#94A3B8; }

        .alert-box { border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; }

        .info-banner {
            background:#EFF6FF; border:1px solid #BFDBFE; border-radius:10px;
            padding:0.8rem 1.1rem; font-size:0.82rem; color:#1D4ED8; margin-bottom:1.5rem;
            display:flex; align-items:flex-start; gap:8px;
        }
    </style>

    <style>
        .expiry-alert {
            border-radius:12px; padding:0.9rem 1.1rem; margin-bottom:1rem;
            display:flex; align-items:flex-start; gap:10px; font-size:0.83rem;
        }
        .expiry-alert-red    { background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; }
        .expiry-alert-orange { background:#FFF7ED; border:1px solid #FED7AA; color:#92400E; }
        .expiry-alert ul { margin:0.35rem 0 0 0; padding-left:1.1rem; }
        .expiry-alert li { margin-bottom:0.15rem; }
    </style>

    @if(session('toast'))
        @php $t = session('toast'); @endphp
        <div class="alert-box {{ $t['type'] === 'success' ? 'alert-success' : 'alert-error' }}">
            {{ $t['message'] }}
        </div>
    @endif

    {{-- ─── Alertas de expiración ─── --}}
    @php
        $expirados = $firmantes->filter(fn($f) => $f->cert_expira_en && $f->diasParaExpirar() !== null && $f->diasParaExpirar() < 0);
        $porVencer = $firmantes->filter(fn($f) => $f->cert_expira_en && $f->diasParaExpirar() !== null && $f->diasParaExpirar() >= 0 && $f->diasParaExpirar() <= 30);
    @endphp

    @if($expirados->isNotEmpty())
        <div class="expiry-alert expiry-alert-red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <div>
                <strong>Certificado(s) vencido(s)</strong> — estos firmantes no pueden firmar diplomas:
                <ul>
                    @foreach($expirados as $f)
                        <li>{{ $f->nombre }} (venció {{ $f->cert_expira_en->format('d/m/Y') }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($porVencer->isNotEmpty())
        <div class="expiry-alert expiry-alert-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong>Certificado(s) por vencer</strong> — renueva la e.firma SAT antes de la fecha indicada:
                <ul>
                    @foreach($porVencer as $f)
                        <li>{{ $f->nombre }} — vence en {{ $f->diasParaExpirar() }} día(s) ({{ $f->cert_expira_en->format('d/m/Y') }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="info-banner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;">
            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
        </svg>
        Los firmantes son los DPA o responsables que firman digitalmente los diplomas con su e.firma SAT.
        Los archivos .cer y .key se guardan de forma segura fuera del acceso público.
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <p style="font-size:0.8rem; color:#64748b; margin:0;">{{ auth()->user()->department->name ?? '' }}</p>
        <a href="{{ route('admin.firmantes.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Registrar firmante
        </a>
    </div>

    @if($firmantes->isEmpty())
        <div class="data-card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:48px;height:48px;margin:0 auto 1rem;display:block;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <p style="font-size:0.95rem; margin-bottom:0.25rem;">Sin firmantes registrados</p>
                <p style="font-size:0.82rem;">Registra el primer DPA con el botón de arriba.</p>
            </div>
        </div>
    @else
        <div class="data-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>RFC</th>
                        <th>Certificado vence</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($firmantes as $f)
                        @php
                            $dias   = $f->diasParaExpirar();
                            $vigente = $f->certVigente();
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:34px;height:34px;border-radius:50%;background:var(--brand-bg);
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:0.8rem;font-weight:700;color:var(--brand);flex-shrink:0;">
                                        {{ strtoupper(substr($f->nombre, 0, 1)) }}
                                    </div>
                                    <span style="font-weight:500;color:#1E293B;">{{ $f->nombre }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b;font-size:0.82rem;">{{ $f->cargo }}</td>
                            <td style="font-family:monospace;font-size:0.82rem;">{{ $f->rfc ?? '—' }}</td>
                            <td>
                                @if($f->cert_expira_en)
                                    @if($dias === null || $dias < 0)
                                        <span class="badge badge-red">Vencido</span>
                                    @elseif($dias <= 30)
                                        <span class="badge badge-orange" title="{{ $f->cert_expira_en->format('d/m/Y') }}">
                                            Vence en {{ $dias }}d
                                        </span>
                                    @else
                                        <span style="font-size:0.82rem;color:#475569;">{{ $f->cert_expira_en->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                    <span style="color:#94A3B8;font-size:0.82rem;">Sin datos</span>
                                @endif
                            </td>
                            <td>
                                @if(!$f->activo)
                                    <span class="badge badge-gray">Inactivo</span>
                                @elseif(!$vigente)
                                    <span class="badge badge-red">Cert. inválido</span>
                                @else
                                    <span class="badge badge-green">Activo</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;gap:1rem;justify-content:flex-end;align-items:center;">
                                    <a href="{{ route('admin.firmantes.show', $f) }}" class="action-link link-view">Ver</a>
                                    <a href="{{ route('admin.firmantes.edit', $f) }}" class="action-link link-edit">Editar</a>
                                    <form action="{{ route('admin.firmantes.toggle-activo', $f) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="link-toggle">
                                            {{ $f->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.firmantes.destroy', $f) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar firmante «{{ $f->nombre }}»?')" style="margin:0;">
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
    @endif

</x-app-layout>
