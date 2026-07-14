<x-app-layout>
    <x-slot name="header">
        <h2>Diplomas generados</h2>
    </x-slot>

    <style>
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
        .btn-danger {
            display:inline-flex; align-items:center; gap:6px;
            background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            cursor:pointer; transition:background 0.1s;
        }
        .btn-danger:hover { background:#FEE2E2; }

        .summary-card {
            background:#fff; border:1px solid #E8EDF4; border-radius:12px;
            padding:1rem 1.25rem; margin-bottom:1.25rem;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .summary-info { font-size:0.875rem; color:#475569; }
        .summary-info strong { color:#1E293B; }
        .actions-bar { display:flex; gap:0.75rem; flex-wrap:wrap; }

        .data-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .data-table thead th {
            background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.7rem 1.1rem; text-align:left;
            font-size:0.7rem; font-weight:600; color:#4A6585;
            letter-spacing:0.07em; text-transform:uppercase;
        }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.75rem 1.1rem; color:#334155; vertical-align:middle; }

        .badge { display:inline-flex; align-items:center; gap:4px; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-gray   { background:#F1F5F9; color:#94A3B8; }
        .badge-orange { background:#FFF7ED; color:#EA580C; }
        .badge-blue   { background:#EFF6FF; color:#1D4ED8; }

        .action-link { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view { color:var(--brand); }
        .link-sign { color:#059669; }

        .firma-tag {
            display:inline-flex; align-items:center; gap:4px;
            font-size:0.72rem; font-weight:600; padding:0.18rem 0.55rem;
            border-radius:20px;
        }
        .firma-si  { background:#F0FDF4; color:#16A34A; }
        .firma-no  { background:#F1F5F9; color:#94A3B8; }

        .alert-box { border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; }
        .alert-info    { background:#EFF6FF; border:1px solid #BFDBFE; color:#1D4ED8; }

        .empty-state { text-align:center; padding:3rem; color:#94A3B8; }
    </style>

    @if(session('toast'))
        @php $t = session('toast'); @endphp
        <div class="alert-box alert-{{ $t['type'] === 'success' ? 'success' : ($t['type'] === 'info' ? 'info' : 'error') }}">
            {{ $t['message'] }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert-box alert-success">{{ session('success') }}</div>
    @endif

    {{-- Barra de resumen y acciones --}}
    <div class="summary-card">
        <div class="summary-info">
            Curso: <strong>{{ $curso->nombre }}</strong> &mdash;
            Plantilla: <strong>{{ $template->nombre }}</strong> &mdash;
            <strong>{{ $diplomas->count() }}</strong> diploma(s) &mdash;
            Firmados: <strong>{{ $diplomas->where('tiene_firma_digital', true)->count() }}</strong>
        </div>
        <div class="actions-bar">
            <a href="{{ route('admin.diplomas.mass.download-combined', $curso) }}" class="btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25"/>
                </svg>
                PDF combinado
            </a>
            <a href="{{ route('admin.diplomas.mass.download-all', $curso) }}" class="btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/>
                </svg>
                Descargar ZIP
            </a>
            <form action="{{ route('admin.diplomas.mass.regenerate', $curso) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar todos los diplomas y regenerarlos?')" style="margin:0;">
                @csrf
                <button type="submit" class="btn-danger">Regenerar</button>
            </form>
        </div>
    </div>

    @if($diplomas->isEmpty())
        <div class="data-card">
            <div class="empty-state">No se encontraron diplomas para este curso.</div>
        </div>
    @else
        <div class="data-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Alumno</th>
                        <th>Emisión</th>
                        <th>Firma digital</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diplomas as $diploma)
                        <tr>
                            <td style="font-family:monospace;font-size:0.8rem;color:#475569;">
                                {{ $diploma->folio }}
                            </td>
                            <td style="font-weight:500;color:#1E293B;">
                                {{ $diploma->alumno->full_name ?? '—' }}
                            </td>
                            <td style="font-size:0.82rem;color:#64748b;">
                                {{ $diploma->fecha_emision?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                @if($diploma->estaFirmado())
                                    <span class="firma-tag firma-si">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:10px;height:10px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        {{ $diploma->firmante->nombre ?? 'Firmado' }}
                                    </span>
                                    @if($diploma->firmado_en)
                                        <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;">
                                            {{ $diploma->firmado_en->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="firma-tag firma-no">Sin firma</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-green">{{ ucfirst($diploma->estado) }}</span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;gap:1rem;justify-content:flex-end;align-items:center;">
                                    @if(!$diploma->estaFirmado())
                                        {{-- Enlace a la página de firmantes para elegir quién firma --}}
                                        <a href="{{ route('admin.firmantes.index') }}" class="action-link link-sign"
                                           title="Firmar este diploma">
                                            Firmar
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.diplomas.mass.download', $diploma) }}"
                                       class="action-link link-view">PDF</a>
                                    <a href="{{ route('verificar', $diploma->token_qr) }}"
                                       target="_blank" class="action-link" style="color:#64748b;">Verificar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
