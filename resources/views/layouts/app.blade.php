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

        {{-- Confirm Modal --}}
        <div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:16px;width:90%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;">
                <div style="padding:1.5rem 1.5rem 0.5rem;text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#FEF2F2;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" style="width:24px;height:24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <p id="confirmMessage" style="font-size:0.95rem;color:#1e293b;font-weight:500;margin:0 0 0.25rem;line-height:1.5;"></p>
                </div>
                <div style="padding:0.75rem 1.5rem 1.5rem;display:flex;gap:0.75rem;">
                    <button id="confirmCancelBtn" type="button" onclick="closeConfirmModal()"
                            style="flex:1;padding:0.6rem 1rem;border:1px solid #DDE3EF;border-radius:10px;background:#fff;color:#64748b;font-size:0.85rem;font-weight:600;cursor:pointer;">
                        Cancelar
                    </button>
                    <button id="confirmAcceptBtn" type="button"
                            style="flex:1;padding:0.6rem 1rem;border:none;border-radius:10px;background:#DC2626;color:#fff;font-size:0.85rem;font-weight:600;cursor:pointer;">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>

        {{-- Toast Container --}}
        <div id="toastContainer" style="position:fixed;top:1rem;right:1rem;z-index:99998;display:flex;flex-direction:column;gap:0.5rem;"></div>

        <script>
            function openSidebar()  { document.getElementById('app-sidebar').classList.add('open'); document.getElementById('sidebar-overlay').classList.add('visible'); }
            function closeSidebar() { document.getElementById('app-sidebar').classList.remove('open'); document.getElementById('sidebar-overlay').classList.remove('visible'); }

            var pendingForm = null;

            function confirmAction(event, message, options) {
                if (event) event.preventDefault();
                var form = event ? (event.target.tagName === 'FORM' ? event.target : event.target.closest('form')) : null;
                pendingForm = form;
                if (!form) return false;

                document.getElementById('confirmMessage').textContent = message;
                var modal = document.getElementById('confirmModal');
                modal.style.display = 'flex';

                var acceptBtn = document.getElementById('confirmAcceptBtn');
                var cancelBtn = document.getElementById('confirmCancelBtn');

                var btnColor = options && options.btnColor ? options.btnColor : '#DC2626';
                var acceptText = options && options.acceptText ? options.acceptText : 'Aceptar';
                var cancelText = options && options.cancelText ? options.cancelText : 'Cancelar';

                acceptBtn.textContent = acceptText;
                acceptBtn.style.background = btnColor;
                cancelBtn.textContent = cancelText;

                if (options && options.hideCancel) {
                    cancelBtn.style.display = 'none';
                } else {
                    cancelBtn.style.display = '';
                }
            }

            document.getElementById('confirmAcceptBtn').addEventListener('click', function() {
                var form = pendingForm;
                document.getElementById('confirmModal').style.display = 'none';
                pendingForm = null;
                if (form) {
                    setTimeout(function() { form.submit(); }, 100);
                }
            });

            function closeConfirmModal() {
                document.getElementById('confirmModal').style.display = 'none';
                pendingForm = null;
            }

            document.addEventListener('click', function(e) {
                var modal = document.getElementById('confirmModal');
                if (e.target === modal) closeConfirmModal();
            });

            function showToast(message, type) {
                type = type || 'success';
                var container = document.getElementById('toastContainer');
                var colors = { success: 'bg:#DCFCE7;color:#166534', error: 'bg:#FEE2E2;color:#991B1B', info: 'bg:#EFF6FF;color:#1D4ED8', warning: 'bg:#FEF3C7;color:#92400E' };
                var c = colors[type] || colors.info;
                var toast = document.createElement('div');
                toast.style.cssText = c + ';padding:0.75rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.1);display:flex;align-items:center;gap:0.75rem;max-width:360px;animation:fadeIn 0.2s ease;';
                toast.innerHTML = '<span style="flex:1;">' + message + '</span><button onclick="this.parentElement.remove()" style="border:none;background:none;cursor:pointer;font-size:1.1rem;line-height:1;opacity:0.6;">&times;</button>';
                container.appendChild(toast);
                setTimeout(function() { if (toast.parentElement) { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); } }, 5000);
            }

            @if(session('toast'))
            (function() { showToast('{{ session('toast')['message'] }}', '{{ session('toast')['type'] }}'); })();
            @endif

            var style = document.createElement('style');
            style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }';
            document.head.appendChild(style);
        </script>
    </body>
</html>
