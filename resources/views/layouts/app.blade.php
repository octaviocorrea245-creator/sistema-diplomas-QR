@php
// ── Paleta dinámica según departamento del usuario ──
$brand = [
    'color'    => '#1A56B0',
    'dark'     => '#0D1B35',
    'bg'       => '#EEF3FB',
    'light'    => '#93C5FD',
    'alpha'    => 'rgba(26,86,176,0.18)',
    'gradient' => 'linear-gradient(135deg, #0D1B35 0%, #1A56B0 100%)',
    'abbr'     => 'UPGP',
    'dept'     => null,
];

if (auth()->check() && auth()->user()->hasRole('admin') && auth()->user()->department) {
    $dn = mb_strtolower(auth()->user()->department->name);
    if (str_contains($dn, 'animac')) {
        $brand = ['color'=>'#E8960A','plate'=>'#B87208','dark'=>'#0D1B35','bg'=>'#FEF9EC','light'=>'#FFD580',
                  'alpha'=>'rgba(232,150,10,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#E8960A 100%)',
                  'abbr'=>'IAEV','dept'=>auth()->user()->department->name];
    } elseif (str_contains($dn, 'biotecn')) {
        $brand = ['color'=>'#5EA825','plate'=>'#3D7A14','dark'=>'#0D1B35','bg'=>'#F1F9EA','light'=>'#BBF7A0',
                  'alpha'=>'rgba(94,168,37,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#5EA825 100%)',
                  'abbr'=>'IBIO','dept'=>auth()->user()->department->name];
    } elseif (str_contains($dn, 'manufactura')) {
        $brand = ['color'=>'#C62828','plate'=>'#8B1A1A','dark'=>'#0D1B35','bg'=>'#FEECEB','light'=>'#FFAAAA',
                  'alpha'=>'rgba(198,40,40,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#C62828 100%)',
                  'abbr'=>'IMA','dept'=>auth()->user()->department->name];
    } elseif (str_contains($dn, 'comercio')) {
        $brand = ['color'=>'#6A1B9A','plate'=>'#480D6D','dark'=>'#0D1B35','bg'=>'#F5EEF8','light'=>'#D7AAEE',
                  'alpha'=>'rgba(106,27,154,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#6A1B9A 100%)',
                  'abbr'=>'CIA','dept'=>auth()->user()->department->name];
    } elseif (str_contains($dn, 'datos') || str_contains($dn, 'artificial')) {
        $brand = ['color'=>'#00838F','plate'=>'#005F6B','dark'=>'#0D1B35','bg'=>'#E0F7FA','light'=>'#80DEEA',
                  'alpha'=>'rgba(0,131,143,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#00838F 100%)',
                  'abbr'=>'IDIA','dept'=>auth()->user()->department->name];
    } else {
        $brand = ['color'=>'#0277BD','plate'=>'#01579B','dark'=>'#0D1B35','bg'=>'#E1F5FE','light'=>'#87CEFA',
                  'alpha'=>'rgba(2,119,189,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#0277BD 100%)',
                  'abbr'=>'TID','dept'=>auth()->user()->department->name];
    }
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Diplomas QR') }} — UPGP</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --navy:      #0D1B35;
                --navy-mid:  #12284F;
                --red:       #C0392B;
                --bg-page:   #F0F4FA;
                --sidebar-w: 260px;

                /* ── Brand dinámico ── */
                --brand:          {{ $brand['color'] }};
                --brand-dark:     {{ $brand['dark'] }};
                --brand-bg:       {{ $brand['bg'] }};
                --brand-light:    {{ $brand['light'] }};
                --brand-alpha:    {{ $brand['alpha'] }};
                --brand-gradient: {{ $brand['gradient'] }};
            }

            *, *::before, *::after { box-sizing: border-box; }
            body { font-family: 'Inter', sans-serif; background: var(--bg-page); margin: 0; }

            #app-sidebar {
                position: fixed; top: 0; left: 0;
                width: var(--sidebar-w); height: 100vh;
                background: linear-gradient(180deg, #0D1B35 0%, #0a1628 100%);
                border-right: 1px solid rgba(255,255,255,0.07);
                display: flex; flex-direction: column;
                z-index: 100; overflow-y: auto;
                transition: transform 0.25s ease;
            }

            #app-main {
                margin-left: var(--sidebar-w);
                min-height: 100vh;
                display: flex; flex-direction: column;
            }
            #app-main > main { flex: 1; padding: 2rem 2.5rem; }

            .page-header {
                background: #fff;
                border-bottom: 2px solid rgba(0,0,0,0.06);
                padding: 1.25rem 2.5rem;
                display: flex; align-items: center; gap: 0.75rem;
            }
            .page-header::before {
                content: '';
                display: block;
                width: 4px; height: 1.4rem;
                background: var(--brand);
                border-radius: 3px;
                flex-shrink: 0;
            }
            .page-header h2 {
                font-family: 'Playfair Display', serif;
                font-size: 1.35rem; color: var(--navy); margin: 0;
            }

            /* Nav links */
            .nav-link { display:flex; align-items:center; gap:10px; padding:0.6rem 0.75rem; border-radius:8px; font-size:0.875rem; font-weight:500; text-decoration:none; transition:background 0.15s; color:#CBD5E1; }
            .nav-link:hover { background: rgba(255,255,255,0.07); color:#E2E8F0; }
            .nav-link.active { background: var(--brand-alpha); color: var(--brand-light); border-left: 3px solid var(--brand); padding-left: calc(0.75rem - 3px); }

            #sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99; }

            @media (max-width: 768px) {
                #app-sidebar { transform: translateX(-100%); }
                #app-sidebar.open { transform: translateX(0); }
                #app-main { margin-left: 0; }
                #app-main > main { padding: 1.25rem 1rem; }
                .page-header { padding: 1rem; }
                #sidebar-overlay.visible { display: block; }
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')
        <div id="sidebar-overlay" onclick="closeSidebar()"></div>

        <div id="app-main">
            @if (isset($header))
                <header class="page-header">{{ $header }}</header>
            @endif
            <main>{{ $slot }}</main>
        </div>

        <script>
            function openSidebar()  { document.getElementById('app-sidebar').classList.add('open'); document.getElementById('sidebar-overlay').classList.add('visible'); }
            function closeSidebar() { document.getElementById('app-sidebar').classList.remove('open'); document.getElementById('sidebar-overlay').classList.remove('visible'); }
        </script>
    </body>
</html>
