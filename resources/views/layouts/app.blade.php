@php
// ── Paleta dinámica según departamento del usuario ──
$brand = [
    'color'    => '#1A56B0',
    'plate'    => '#1A56B0',
    'dark'     => '#0D1B35',
    'bg'       => '#EEF3FB',
    'light'    => '#93C5FD',
    'alpha'    => 'rgba(26,86,176,0.18)',
    'gradient' => 'linear-gradient(135deg, #0D1B35 0%, #1A56B0 100%)',
    'abbr'     => 'UPGP',
    'dept'     => null,
];

if (auth()->check() && auth()->user()->hasRole('admin') && auth()->user()->department) {
    $d = auth()->user()->department;
    $c = $d->color;
    if ($c) {
        $a = ltrim($c, '#');
        $rgb = [hexdec(substr($a,0,2)), hexdec(substr($a,2,2)), hexdec(substr($a,4,2))];
        $brand = [
            'color'    => $c,
            'plate'    => $c,
            'dark'     => '#0D1B35',
            'bg'       => 'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.08)',
            'light'    => 'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.25)',
            'alpha'    => 'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.18)',
            'gradient' => 'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,'.$c.' 100%)',
            'abbr'     => $d->abreviatura,
            'dept'     => $d->name,
        ];
    } else {
        $dn = mb_strtolower($d->name);
        if (str_contains($dn, 'animac')) {
            $brand = ['color'=>'#E8960A','plate'=>'#B87208','dark'=>'#0D1B35','bg'=>'#FEF9EC','light'=>'#FFD580',
                      'alpha'=>'rgba(232,150,10,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#E8960A 100%)',
                      'abbr'=>'IAEV','dept'=>$d->name];
        } elseif (str_contains($dn, 'biotecn')) {
            $brand = ['color'=>'#5EA825','plate'=>'#3D7A14','dark'=>'#0D1B35','bg'=>'#F1F9EA','light'=>'#BBF7A0',
                      'alpha'=>'rgba(94,168,37,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#5EA825 100%)',
                      'abbr'=>'IBIO','dept'=>$d->name];
        } elseif (str_contains($dn, 'manufactura')) {
            $brand = ['color'=>'#C62828','plate'=>'#8B1A1A','dark'=>'#0D1B35','bg'=>'#FEECEB','light'=>'#FFAAAA',
                      'alpha'=>'rgba(198,40,40,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#C62828 100%)',
                      'abbr'=>'IMA','dept'=>$d->name];
        } elseif (str_contains($dn, 'comercio')) {
            $brand = ['color'=>'#6A1B9A','plate'=>'#480D6D','dark'=>'#0D1B35','bg'=>'#F5EEF8','light'=>'#D7AAEE',
                      'alpha'=>'rgba(106,27,154,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#6A1B9A 100%)',
                      'abbr'=>'CIA','dept'=>$d->name];
        } elseif (str_contains($dn, 'datos') || str_contains($dn, 'artificial')) {
            $brand = ['color'=>'#00838F','plate'=>'#005F6B','dark'=>'#0D1B35','bg'=>'#E0F7FA','light'=>'#80DEEA',
                      'alpha'=>'rgba(0,131,143,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#00838F 100%)',
                      'abbr'=>'IDIA','dept'=>$d->name];
        } else {
            $brand = ['color'=>'#0277BD','plate'=>'#01579B','dark'=>'#0D1B35','bg'=>'#E1F5FE','light'=>'#87CEFA',
                      'alpha'=>'rgba(2,119,189,0.18)','gradient'=>'linear-gradient(135deg,#0D1B35 0%,#1A3A6B 55%,#0277BD 100%)',
                      'abbr'=>'TID','dept'=>$d->name];
        }
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

            /* ══════════════════════════════════════
               BRAND UTILITY CLASSES (consistencia UI)
            ══════════════════════════════════════ */
            .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04); border: 1px solid #e5e7eb; }
            .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; font-weight: 600; font-size: 0.95rem; color: #111827; }
            .card-body { padding: 1.25rem; }
            .card-footer { padding: 1rem 1.25rem; border-top: 1px solid #f3f4f6; background: #f9fafb; border-radius: 0 0 12px 12px; }

            .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; transition: all .15s; border: 1px solid transparent; text-decoration: none; line-height: 1.4; }
            .btn-primary { background: var(--brand); color: #fff; border-color: var(--brand); }
            .btn-primary:hover { filter: brightness(1.1); }
            .btn-secondary { background: #fff; color: #374151; border-color: #d1d5db; }
            .btn-secondary:hover { background: #f9fafb; }
            .btn-danger { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
            .btn-danger:hover { background: #fecaca; }
            .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
            .btn-xs { padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: 6px; }

            .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .table-wrap table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
            .table-wrap th { text-align: left; padding: 0.65rem 0.75rem; font-weight: 600; color: #6b7280; background: #f9fafb; border-bottom: 1px solid #e5e7eb; white-space: nowrap; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; }
            .table-wrap td { padding: 0.65rem 0.75rem; border-bottom: 1px solid #f3f4f6; color: #374151; }
            .table-wrap tr:hover td { background: #f9fafb; }

            .badge { display: inline-flex; align-items: center; padding: 0.15rem 0.6rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 600; text-transform: capitalize; }
            .badge-blue { background: #dbeafe; color: #1e40af; }
            .badge-green { background: #d1fae5; color: #065f46; }
            .badge-yellow { background: #fef3c7; color: #92400e; }
            .badge-red { background: #fee2e2; color: #991b1b; }
            .badge-purple { background: #ede9fe; color: #5b21b6; }
            .badge-gray { background: #f3f4f6; color: #374151; }

            .form-group { margin-bottom: 1rem; }
            .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.3rem; }
            .form-input, .form-select { width: 100%; padding: 0.55rem 0.75rem; font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 8px; color: #111827; background: #fff; transition: border-color .15s, box-shadow .15s; }
            .form-input:focus, .form-select:focus { border-color: var(--brand); outline: none; box-shadow: 0 0 0 3px var(--brand-alpha); }
            .form-error { font-size: 0.78rem; color: #dc2626; margin-top: 0.25rem; }

            .alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
            .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
            .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
            .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

            .page-title { font-family: 'Playfair Display', serif; font-size: 1.35rem; color: var(--navy); margin: 0; }
            .text-muted { color: #6b7280; }
            .text-brand { color: var(--brand); }

            .flex { display: flex; }
            .flex-1 { flex: 1; }
            .items-center { align-items: center; }
            .justify-between { justify-content: space-between; }
            .gap-2 { gap: 0.5rem; }
            .gap-3 { gap: 0.75rem; }
            .gap-4 { gap: 1rem; }
            .mb-2 { margin-bottom: 0.5rem; }
            .mb-4 { margin-bottom: 1rem; }
            .mt-4 { margin-top: 1rem; }
            .mt-2 { margin-top: 0.5rem; }
            .w-full { width: 100%; }
            .text-center { text-align: center; }
            .text-sm { font-size: 0.875rem; }
            .text-xs { font-size: 0.75rem; }
            .font-medium { font-weight: 500; }
            .font-semibold { font-weight: 600; }
            .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
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
        @stack('scripts')
        @stack('styles')
    </body>
</html>
