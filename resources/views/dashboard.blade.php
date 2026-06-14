@php
$brand = ['abbr'=>'UPGP','color'=>'#1A56B0','bg'=>'#EEF3FB','light'=>'#93C5FD','dept'=>null];
if (auth()->check() && auth()->user()->hasRole('admin') && auth()->user()->department) {
    $d = auth()->user()->department;
    $c = $d->color;
    if ($c) {
        $a = ltrim($c, '#');
        $rgb = [hexdec(substr($a,0,2)), hexdec(substr($a,2,2)), hexdec(substr($a,4,2))];
        $brand = ['abbr'=>$d->abreviatura,'color'=>$c,'bg'=>'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.08)','light'=>'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.25)','dept'=>$d->name];
    } else {
        $dn = mb_strtolower($d->name);
        if (str_contains($dn, 'animac'))      $brand = ['abbr'=>'IAEV','color'=>'#E8960A','bg'=>'#FEF9EC','light'=>'#FFD580','dept'=>$d->name];
        elseif (str_contains($dn, 'biotecn'))  $brand = ['abbr'=>'IBIO','color'=>'#5EA825','bg'=>'#F1F9EA','light'=>'#BBF7A0','dept'=>$d->name];
        elseif (str_contains($dn, 'manufactura')) $brand = ['abbr'=>'IMA','color'=>'#C62828','bg'=>'#FEECEB','light'=>'#FFAAAA','dept'=>$d->name];
        elseif (str_contains($dn, 'comercio')) $brand = ['abbr'=>'CIA','color'=>'#6A1B9A','bg'=>'#F5EEF8','light'=>'#D7AAEE','dept'=>$d->name];
        elseif (str_contains($dn, 'datos') || str_contains($dn, 'artificial')) $brand = ['abbr'=>'IDIA','color'=>'#00838F','bg'=>'#E0F7FA','light'=>'#80DEEA','dept'=>$d->name];
        else $brand = ['abbr'=>'TID','color'=>'#0277BD','bg'=>'#E1F5FE','light'=>'#87CEFA','dept'=>$d->name];
    }
}
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2>
            @if(Auth::user()->hasRole('supervisor')) Panel de Supervisor
            @elseif(Auth::user()->hasRole('admin')) Panel de Administrador
            @else Mi Panel
            @endif
        </h2>
    </x-slot>

    <style>
        .action-card {
            background: #fff; border-radius: 14px; padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #E8EDF4;
            text-decoration: none; display: block; transition: all 0.15s;
        }
        .action-card:hover { border-color: var(--brand); box-shadow: 0 4px 16px var(--brand-alpha); transform: translateY(-2px); }
        .action-card .icon-box { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; }
        .action-card h3 { font-size:0.95rem; font-weight:600; color:#0D1B35; margin-bottom:0.25rem; }
        .action-card p  { font-size:0.8rem; color:#64748b; margin:0; }

        .welcome-banner {
            background: var(--brand-gradient);
            border-radius: 14px; padding: 1.75rem 2rem; color: #fff;
            position: relative; overflow: hidden; margin-bottom: 2rem;
        }
        .welcome-banner::before {
            content:''; position:absolute; right:-30px; top:-30px;
            width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.04);
        }
        .welcome-banner::after {
            content:''; position:absolute; right:50px; bottom:-50px;
            width:140px; height:140px; border-radius:50%; background:rgba(255,255,255,0.03);
        }
    </style>

    {{-- Banner de bienvenida --}}
    <div class="welcome-banner">
        <div style="font-size:0.72rem; color:rgba(255,255,255,0.55); font-weight:600; letter-spacing:0.08em; margin-bottom:0.5rem; position:relative; z-index:1; text-transform:uppercase;">
            @if(Auth::user()->hasRole('admin') && Auth::user()->department)
                {{ $brand['abbr'] }} — {{ Auth::user()->department->name }}
            @else
                Sistema de Diplomas — UPGP
            @endif
        </div>
        <h1 style="font-family:'Playfair Display',serif; font-size:1.65rem; font-weight:700; margin:0 0 0.4rem; position:relative; z-index:1;">
            Bienvenido, {{ Auth::user()->full_name }}
        </h1>
        <p style="font-size:0.85rem; color:rgba(255,255,255,0.7); margin:0; position:relative; z-index:1;">
            @if(Auth::user()->hasRole('supervisor'))
                Tienes acceso completo al sistema como Supervisor.
            @elseif(Auth::user()->hasRole('admin'))
                Gestiona los cursos y diplomas de tu carrera.
            @else
                Consulta tus diplomas y certificados disponibles.
            @endif
        </p>
        <div style="position:relative; z-index:1; margin-top:1.1rem; display:flex; align-items:center; gap:0.65rem; flex-wrap:wrap;">
            <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:20px; padding:0.28rem 0.8rem; font-size:0.73rem; color:rgba(255,255,255,0.9);">
                <span style="width:6px; height:6px; background:#4ADE80; border-radius:50%; display:inline-block;"></span>
                @if(Auth::user()->hasRole('supervisor')) Supervisor
                @elseif(Auth::user()->hasRole('admin')) Administrador
                @else Beneficiario
                @endif
            </span>
            @if(Auth::user()->hasRole('admin') && Auth::user()->department)
            <span style="display:inline-flex; align-items:center; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:20px; padding:0.28rem 0.8rem; font-size:0.73rem; color:rgba(255,255,255,0.9); font-weight:700; letter-spacing:0.05em;">
                {{ $brand['abbr'] }}
            </span>
            @endif
        </div>
    </div>

    {{-- Accesos rápidos: Supervisor --}}
    @role('supervisor')
    <div style="margin-bottom:1.5rem;">
        <h2 style="font-size:0.95rem; font-weight:600; color:#0D1B35; margin-bottom:1rem;">Accesos rápidos</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1rem;">
            <a href="{{ route('supervisor.admins.index') }}" class="action-card">
                <div class="icon-box" style="background:#EEF3FB;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1A56B0" stroke-width="1.8" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <h3>Administradores</h3>
                <p>Gestionar usuarios con rol admin</p>
            </a>
            <a href="{{ route('supervisor.cursos.index') }}" class="action-card">
                <div class="icon-box" style="background:#FEF3C7;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="1.8" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3>Cursos</h3>
                <p>Ver todos los cursos del sistema</p>
            </a>
            <a href="{{ route('supervisor.alumnos.index') }}" class="action-card">
                <div class="icon-box" style="background:#F0FDF4;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="1.8" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                </div>
                <h3>Alumnos</h3>
                <p>Consultar alumnos registrados</p>
            </a>
            <a href="{{ route('supervisor.departamentos.index') }}" class="action-card">
                <div class="icon-box" style="background:#FDF2F8;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#9333EA" stroke-width="1.8" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                </div>
                <h3>Departamentos</h3>
                <p>Administrar departamentos</p>
            </a>
        </div>
    </div>
    @endrole

    {{-- Accesos rápidos: Admin --}}
    @role('admin')
    <div style="margin-bottom:1.5rem;">
        <h2 style="font-size:0.95rem; font-weight:600; color:#0D1B35; margin-bottom:1rem;">Accesos rápidos</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1rem;">
            <a href="{{ route('admin.cursos.index') }}" class="action-card">
                <div class="icon-box" style="background: var(--brand-bg);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="1.8" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3>Gestionar Cursos</h3>
                <p>Crear, editar y eliminar cursos</p>
            </a>
        </div>
    </div>
    @endrole

    {{-- Beneficiario --}}
    @role('beneficiario')
    <div style="background:#fff; border-radius:14px; padding:2rem; text-align:center; border:1px solid #E8EDF4;">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="1.5" style="width:44px;height:44px;margin:0 auto 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
        </svg>
        <h3 style="font-size:1.05rem; font-weight:600; color:#0D1B35; margin-bottom:0.5rem;">Tus diplomas</h3>
        <p style="color:#64748b; font-size:0.875rem;">Aquí aparecerán tus diplomas y certificados una vez que sean emitidos.</p>
    </div>
    @endrole

</x-app-layout>
