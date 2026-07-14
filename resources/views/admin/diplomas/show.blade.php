<x-app-layout>
    <x-slot name="header">
        <h2>Diploma {{ $diploma->folio }}</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:300px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding:1.5rem; }
        .card-title { font-size:0.7rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.6rem; border-bottom:1px solid #F0F4FA; }

        .info-row { display:flex; gap:8px; margin-bottom:0.8rem; font-size:0.875rem; align-items:flex-start; }
        .info-key { color:#64748b; min-width:120px; flex-shrink:0; font-size:0.82rem; }
        .info-val { color:#1E293B; font-weight:500; word-break:break-all; }
        .mono { font-family:monospace; font-size:0.8rem; letter-spacing:0.03em; }

        .badge { display:inline-flex; align-items:center; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-blue   { background:#EFF6FF; color:#1D4ED8; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }

        .btn-outline {
            display:inline-flex; align-items:center; gap:6px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-outline:hover { background:#F7F9FC; color:#475569; }

        .token-box {
            background:#F8FAFC; border:1px solid #E8EDF4; border-radius:8px;
            padding:0.75rem 1rem; font-family:monospace; font-size:0.75rem;
            color:#475569; word-break:break-all; line-height:1.6;
        }
        .token-box .label { font-family:sans-serif; font-size:0.65rem; font-weight:700;
            color:#94A3B8; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:4px; }

        .data-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
        .data-table thead th { background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.6rem 1rem; text-align:left; font-size:0.7rem; font-weight:600;
            color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.65rem 1rem; color:#334155; }

        .avatar {
            width:44px; height:44px; border-radius:50%; background:var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; color:var(--brand); flex-shrink:0;
        }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.diplomas.index') }}">Diplomas</a>
        <span class="breadcrumb-sep">/</span>
        <span class="mono">{{ $diploma->folio }}</span>
    </div>

    <div class="page-grid">

        {{-- Panel izquierdo --}}
        <div>
            {{-- Diploma info --}}
            <div class="card" style="margin-bottom:1rem;">
                <div class="card-body">
                    <p class="card-title">Información del diploma</p>

                    <div class="info-row">
                        <span class="info-key">Folio</span>
                        <span class="info-val mono">{{ $diploma->folio }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Estado</span>
                        <span>
                            @php
                                $badgeClass = match($diploma->estado) {
                                    'emitido'   => 'badge-green',
                                    'revocado'  => 'badge-red',
                                    'reemitido' => 'badge-blue',
                                    default     => 'badge-gray',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($diploma->estado) }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Fecha emisión</span>
                        <span class="info-val">{{ $diploma->fecha_emision?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Emitido por</span>
                        <span class="info-val">{{ $diploma->emisor->full_name ?? '—' }}</span>
                    </div>

                    {{-- Firma digital --}}
                    @if($diploma->estaFirmado())
                        <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #F0F4FA;">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:0.75rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                                <span style="font-size:0.72rem; font-weight:700; color:#16A34A; text-transform:uppercase; letter-spacing:0.07em;">Firma digital</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Firmante</span>
                                <span class="info-val">{{ $diploma->firmante->nombre ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Firmado</span>
                                <span class="info-val">{{ $diploma->firmado_en?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>
                            @if($diploma->cert_serie_usada)
                                <div class="info-row">
                                    <span class="info-key">Certificado</span>
                                    <span class="info-val mono" style="font-size:0.7rem;">{{ $diploma->cert_serie_usada }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Acciones</p>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <a href="{{ route('admin.diplomas.mass.download', $diploma) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Descargar PDF
                        </a>
                        <a href="{{ route('verificar', $diploma->token_qr) }}" target="_blank" class="btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Ver verificación pública
                        </a>
                        @if(!$diploma->estaFirmado())
                            <a href="{{ route('admin.firmantes.index') }}" class="btn-outline" style="color:#059669;border-color:#BBF7D0;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                                Aplicar firma digital
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Alumno --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Alumno</p>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem;">
                        <div class="avatar">{{ strtoupper(substr($diploma->alumno->full_name ?? 'A', 0, 1)) }}</div>
                        <div>
                            <div style="font-weight:600;color:#1E293B;font-size:0.95rem;">{{ $diploma->alumno->full_name ?? '—' }}</div>
                            <div style="font-size:0.78rem;color:#64748b;">{{ $diploma->alumno->username ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Curso --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Curso</p>
                    <div class="info-row">
                        <span class="info-key">Nombre</span>
                        <span class="info-val">{{ $diploma->curso->nombre ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Horas</span>
                        <span class="info-val">{{ $diploma->curso->horas ?? '—' }} h</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Inicio</span>
                        <span class="info-val">{{ $diploma->curso->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Fin</span>
                        <span class="info-val">{{ $diploma->curso->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Token QR --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Token QR</p>
                    <div class="token-box">
                        <span class="label">Token de verificación</span>
                        {{ $diploma->token_qr }}
                    </div>
                    <p style="font-size:0.72rem;color:#94A3B8;margin-top:0.5rem;">
                        URL pública: {{ route('verificar', $diploma->token_qr) }}
                    </p>
                </div>
            </div>

            {{-- Reimpresiones --}}
            @if($diploma->reimpresiones->isNotEmpty())
                <div class="card">
                    <div class="card-body" style="padding-bottom:0;">
                        <p class="card-title">Reimpresiones ({{ $diploma->reimpresiones->count() }})</p>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diploma->reimpresiones as $r)
                                <tr>
                                    <td style="white-space:nowrap;">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="color:#64748b;">{{ $r->motivo ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
