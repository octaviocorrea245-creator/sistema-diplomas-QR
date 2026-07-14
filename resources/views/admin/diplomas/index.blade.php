<x-app-layout>
    <x-slot name="header">
        <h2>Diplomas</h2>
    </x-slot>

    <style>
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-secondary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand-bg); color: var(--brand); border:1px solid var(--brand-alpha);
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:background 0.15s;
        }
        .btn-secondary:hover { background: var(--brand-alpha); color: var(--brand); }

        .data-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
        }
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

        .badge {
            display:inline-flex; align-items:center; padding:0.2rem 0.65rem;
            border-radius:20px; font-size:0.72rem; font-weight:600;
        }
        .badge-green  { background:#F0FDF4; color:#16A34A; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-blue   { background:#EFF6FF; color:#2563EB; }
        .badge-gray   { background:#F1F5F9; color:#64748B; }

        .folio-code {
            font-family: 'Courier New', monospace; font-size:0.75rem; font-weight:600;
            color:#1E293B; background:#F1F5F9; padding:0.15rem 0.5rem; border-radius:5px;
        }

        .alert-success {
            background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem;
        }
        .empty-state { text-align:center; padding:3.5rem 1rem; color:#94A3B8; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.75rem;">
        <p style="font-size:0.82rem; color:#64748b; margin:0;">
            Diplomas emitidos para {{ auth()->user()->department->name ?? 'tu carrera' }}
        </p>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('admin.diplomas.mass.create') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                Emisión masiva
            </a>
            <a href="{{ route('admin.diplomas.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Emitir Diploma
            </a>
        </div>
    </div>

    <div class="data-card">
        @if($diplomas->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:48px;height:48px;display:block;margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
                <p style="font-size:0.95rem; margin-bottom:0.25rem;">No hay diplomas emitidos</p>
                <p style="font-size:0.82rem;">Emite el primer diploma usando los botones de arriba.</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Fecha de emisión</th>
                        <th style="text-align:right;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diplomas as $diploma)
                    <tr>
                        <td><span class="folio-code">{{ $diploma->folio }}</span></td>
                        <td style="font-weight:500; color:#1E293B;">{{ $diploma->alumno->full_name ?? '—' }}</td>
                        <td style="color:#64748b;">{{ $diploma->curso->nombre ?? '—' }}</td>
                        <td>
                            @php
                                $badgeClass = match($diploma->estado) {
                                    'emitido'   => 'badge-green',
                                    'revocado'  => 'badge-red',
                                    'reemitido' => 'badge-blue',
                                    default     => 'badge-gray',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($diploma->estado) }}</span>
                        </td>
                        <td style="color:#64748b; font-size:0.82rem;">{{ $diploma->fecha_emision->format('d/m/Y') }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.diplomas.show', $diploma) }}"
                               style="font-size:0.8rem; font-weight:500; color: var(--brand); text-decoration:none;"
                               onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div style="margin-top:1rem;">{{ $diplomas->links() }}</div>

</x-app-layout>
