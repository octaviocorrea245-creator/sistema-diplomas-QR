<x-app-layout>
    <x-slot name="header">
        <h2>Administradores</h2>
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
        .btn-danger { display:inline-flex; align-items:center; gap:4px; background:transparent; color:#C0392B; padding:0.35rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; border:1px solid rgba(192,57,43,0.25); cursor:pointer; transition:all 0.15s; }
        .btn-danger:hover { background:rgba(192,57,43,0.08); }
    </style>

    <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Lista de Administradores</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">Usuarios con rol de administrador en el sistema</p>
            </div>
            <a href="{{ route('supervisor.admins.create') }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#1A56B0; color:#fff; padding:0.5rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:500; text-decoration:none; transition:background 0.15s;"
               onmouseover="this.style.background='#1547A0'" onmouseout="this.style.background='#1A56B0'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nuevo Admin
            </a>
        </div>

        @if(session('success'))
        <div style="margin:1rem 1.5rem 0; padding:0.75rem 1rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:8px; color:#166534; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

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
                    @forelse($admins as $admin)
                    @php $c = $admin->department ? $deptColor($admin->department->name) : null; @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:{{ $c ? $c['bg'] : '#EEF3FB' }}; color:{{ $c ? $c['accent'] : '#1A56B0' }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                    {{ strtoupper(substr($admin->full_name, 0, 1)) }}
                                </div>
                                <span style="font-weight:500;">{{ $admin->full_name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b; font-family:monospace; font-size:0.82rem;">{{ $admin->username }}</td>
                        <td>
                            @if($admin->department && $c)
                                <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $c['bg'] }}; color:{{ $c['accent'] }}; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.75rem; font-weight:600; border:1px solid {{ $c['accent'] }}33;">
                                    <span style="font-weight:800; font-size:0.68rem; letter-spacing:0.04em;">{{ $c['abbr'] }}</span>
                                    <span style="color:inherit; opacity:0.8;">{{ $admin->department->name }}</span>
                                </span>
                            @else
                                <span style="color:#94a3b8; font-size:0.82rem;">Sin departamento</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('supervisor.admins.destroy', $admin) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar a {{ $admin->full_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:3rem; color:#94a3b8;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            No hay administradores registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
