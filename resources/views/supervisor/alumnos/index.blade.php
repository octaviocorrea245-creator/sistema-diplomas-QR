<x-app-layout>
    <x-slot name="header">
        <h2>Alumnos</h2>
    </x-slot>

    @php
    $deptColor = function($name) {
        $n = mb_strtolower($name ?? '');
        if (str_contains($n, 'animac'))      return ['accent'=>'#F5A623','bg'=>'#FEF9EC','abbr'=>'IAEV'];
        if (str_contains($n, 'biotecn'))     return ['accent'=>'#7EC441','bg'=>'#F1F9EA','abbr'=>'IBIO'];
        if (str_contains($n, 'manufactura')) return ['accent'=>'#E53935','bg'=>'#FEECEB','abbr'=>'IMA'];
        if (str_contains($n, 'comercio'))    return ['accent'=>'#8E44AD','bg'=>'#F5EEF8','abbr'=>'CIA'];
        if (str_contains($n, 'datos') || str_contains($n, 'artificial')) return ['accent'=>'#00BCD4','bg'=>'#E0F7FA','abbr'=>'IDIA'];
        return ['accent'=>'#03A9F4','bg'=>'#E1F5FE','abbr'=>'TID'];
    };
    @endphp

    <style>
        .upgp-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .upgp-table thead th { background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left; }
        .upgp-table tbody td { padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9; color:#1e293b; vertical-align:middle; }
        .upgp-table tbody tr:last-child td { border-bottom:none; }
        .upgp-table tbody tr:hover td { background:#F8FAFC; }
        .btn-link { display:inline-flex; align-items:center; gap:4px; color:#1A56B0; padding:0.3rem 0.65rem; border-radius:6px; font-size:0.8rem; font-weight:500; border:1px solid rgba(26,86,176,0.25); text-decoration:none; transition:all 0.15s; }
        .btn-link:hover { background:rgba(26,86,176,0.08); }
    </style>

    <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9;">
            <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Lista General de Alumnos</h3>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">Beneficiarios registrados en el sistema</p>
        </div>

        <div style="overflow-x:auto; padding-bottom:0.5rem;">
            <table class="upgp-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Carrera / Departamento</th>
                        <th style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnos as $alumno)
                    @php $c = $alumno->department ? $deptColor($alumno->department->name) : null; @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:{{ $c ? $c['bg'] : '#F1F5F9' }}; color:{{ $c ? $c['accent'] : '#64748b' }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                    {{ strtoupper(substr($alumno->full_name, 0, 1)) }}
                                </div>
                                <span style="font-weight:500;">{{ $alumno->full_name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b; font-family:monospace; font-size:0.82rem;">{{ $alumno->username }}</td>
                        <td>
                            @if($alumno->department && $c)
                                <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $c['bg'] }}; color:{{ $c['accent'] }}; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.75rem; font-weight:600; border:1px solid {{ $c['accent'] }}33;">
                                    <span style="font-weight:800; font-size:0.68rem; letter-spacing:0.04em;">{{ $c['abbr'] }}</span>
                                    <span style="color:inherit; opacity:0.8;">{{ $alumno->department->name }}</span>
                                </span>
                            @else
                                <span style="color:#94a3b8; font-size:0.82rem;">Sin departamento</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('supervisor.alumnos.show', $alumno) }}" class="btn-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:3rem; color:#94a3b8;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                            No hay alumnos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
