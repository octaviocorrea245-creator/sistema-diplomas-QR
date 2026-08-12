<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Diplomas QR — UPGP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════════════
           PANEL IZQUIERDO
        ══════════════════════════════════════ */
        .panel-left {
            flex: 1.1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 3rem;
            overflow: hidden;
            min-width: 380px;
            background: #060e1d;
        }

        /* Gradiente de fondo */
        .panel-left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, #0d1b35 60%, #12284f 100%);
        }

        /* Línea azul izquierda decorativa */
        .panel-left::before {
            content: '';
            position: absolute;
            left: 0; top: 15%; bottom: 15%;
            width: 3px;
            background: linear-gradient(180deg, transparent, #1a56b0 30%, #c0392b 70%, transparent);
            opacity: 0.6;
        }

        .panel-left-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }
        .panel-left-top svg {
            fill: #ffffff;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
        }
        .brand-name {
            font-size: 0.78rem;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.3;
            letter-spacing: 0.02em;
        }
        .brand-name span {
            display: block;
            font-size: 0.65rem;
            font-weight: 400;
            color: #6b9fd4;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 0.1rem;
        }

        .panel-left-center {
            position: relative;
            z-index: 2;
            text-align: left;
        }

        .panel-left-center .eyebrow {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #4a85c9;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .panel-left-center .eyebrow::before {
            content: '';
            display: inline-block;
            width: 24px;
            height: 1.5px;
            background: #c0392b;
        }

        .panel-left-center h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }
        .panel-left-center h1 em {
            font-style: normal;
            color: #5ba3e0;
        }

        .panel-left-center p {
            font-size: 0.82rem;
            color: #5a7fa8;
            line-height: 1.75;
            font-weight: 400;
            max-width: 320px;
        }

        .accent-divider {
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, #c0392b, #e74c3c);
            border-radius: 2px;
            margin: 1.25rem 0;
        }

        /* Píldoras de features */
        .features-list {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .feature-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #1a56b0;
            flex-shrink: 0;
        }
        .feature-item span {
            font-size: 0.78rem;
            color: #5a7fa8;
            letter-spacing: 0.02em;
        }

        .panel-left-bottom {
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-left-bottom p {
            font-size: 0.68rem;
            color: #2d4a6b;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .panel-left-bottom .version {
            font-size: 0.65rem;
            color: #1e3355;
            font-family: monospace;
        }

        /* ══════════════════════════════════════
           PANEL DERECHO
        ══════════════════════════════════════ */
        .panel-right {
            flex: 1;
            background: #f4f6fb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            position: relative;
        }

        /* Patrón de puntos sutil */
        .panel-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(26,86,176,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow:
                0 0 0 1px rgba(13,27,53,0.06),
                0 8px 32px rgba(13,27,53,0.10),
                0 1px 3px rgba(13,27,53,0.06);
            padding: 2.75rem 2.75rem;
            overflow: hidden;
        }

        /* Borde superior de color */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1a56b0 0%, #2e86de 50%, #c0392b 100%);
        }

        .card-header {
            margin-bottom: 2rem;
        }
        .card-header .step-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #1a56b0;
            margin-bottom: 0.6rem;
        }
        .card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #0d1b35;
            line-height: 1.2;
            margin-bottom: 0.4rem;
        }
        .card-header p {
            font-size: 0.835rem;
            color: #8496b0;
            font-weight: 400;
        }

        /* ── Inputs ── */
        .field-group {
            margin-bottom: 1.25rem;
        }
        .field-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2d3e58;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.45rem;
        }
        .field-wrapper {
            position: relative;
        }
        .field-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #8496b0;
            pointer-events: none;
        }
        .field-wrapper input {
            width: 100%;
            padding: 0.72rem 1rem 0.72rem 2.6rem;
            border: 1.5px solid #dde3ee;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #0d1b35;
            background: #f8fafd;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            letter-spacing: 0.01em;
        }
        .field-wrapper input::placeholder { color: #b0bed4; }
        .field-wrapper input:focus {
            border-color: #1a56b0;
            box-shadow: 0 0 0 3.5px rgba(26,86,176,0.10);
            background: #ffffff;
        }
        .field-wrapper input:focus + .field-focus-ring {}

        /* ── Checkbox row ── */
        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0.5rem 0 1.5rem;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .checkbox-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #1a56b0;
            cursor: pointer;
        }
        .checkbox-label span {
            font-size: 0.82rem;
            color: #4a5f7a;
        }
        .link-forgot {
            font-size: 0.82rem;
            color: #1a56b0;
            text-decoration: none;
            font-weight: 500;
            transition: color .15s;
        }
        .link-forgot:hover { color: #0d3d8a; text-decoration: underline; }

        /* ── Botón ── */
        .btn-submit {
            width: 100%;
            padding: 0.82rem;
            background: linear-gradient(135deg, #1a56b0 0%, #0d3d8a 100%);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(26,86,176,0.30);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1.5px);
            box-shadow: 0 8px 24px rgba(26,86,176,0.38);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg {
            width: 16px; height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
        }

        /* ── Error ── */
        .field-error {
            font-size: 0.78rem;
            color: #c0392b;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* ── Status message ── */
        .status-msg {
            background: #eef6ff;
            border: 1px solid rgba(26,86,176,0.2);
            color: #0d3d8a;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            font-size: 0.83rem;
            margin-bottom: 1.25rem;
        }

        /* ── Footer del card ── */
        .card-footer-note {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #edf0f7;
            text-align: center;
            font-size: 0.75rem;
            color: #a0aec0;
        }

        /* ── Copyright ── */
        .page-footer {
            position: relative;
            z-index: 1;
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.72rem;
            color: #b0bed4;
        }
    </style>
</head>
<body>

    <!-- ══ Panel izquierdo: branding ══ -->
    <div class="panel-left">
        <div class="panel-left-overlay"></div>

        <!-- Top: logo + nombre -->
        <div class="panel-left-top">
            <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
                <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z"/>
            </svg>
            <div class="brand-name">
                Sistema Diplomas QR
                <span>Universidad Politécnica Gómez Palacio</span>
            </div>
        </div>

        <!-- Centro: texto principal -->
        <div class="panel-left-center">
            <p class="eyebrow">Acceso institucional</p>
            <h1>Bienvenido al<br>portal <em>oficial</em></h1>
            <div class="accent-divider"></div>
            <p>Plataforma de emisión y verificación de diplomas digitales con código QR para la comunidad universitaria.</p>

            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    <span>Emisión de diplomas con QR único</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot" style="background:#c0392b;"></div>
                    <span>Verificación institucional en tiempo real</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    <span>Descarga en PDF con firma digital</span>
                </div>
            </div>
        </div>

        <!-- Bottom: info -->
        <div class="panel-left-bottom">
            <p>Liderazgo e Innovación con Espíritu Humano</p>
            <span class="version">upgp.edu.mx</span>
        </div>
    </div>

    <!-- ══ Panel derecho: formulario ══ -->
    <div class="panel-right">

        <div class="login-card">
            <div class="card-header">
                <p class="step-label">Autenticación</p>
                <h2>Iniciar sesión</h2>
                <p>Ingresa tus credenciales institucionales</p>
            </div>

            {{ $slot }}

            <div class="card-footer-note">
                Acceso exclusivo para personal autorizado de la UPGP
            </div>
            <div style="text-align:center;margin-top:1rem;">
                <a href="{{ route('escanear') }}" style="font-size:0.82rem;color:#1a56b0;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:0.4rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/>
                    </svg>
                    Escanear código QR de diploma
                </a>
            </div>
        </div>

        <p class="page-footer">
            © {{ date('Y') }} Universidad Politécnica Gómez Palacio · Todos los derechos reservados
        </p>
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

        var style = document.createElement('style');
        style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }';
        document.head.appendChild(style);
    </script>
</body>
</html>
