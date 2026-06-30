<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Diplomas QR — UPGP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0d1b35;
            color: #ffffff;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2.5rem;
            background: rgba(13, 27, 53, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .navbar-brand svg {
            fill: #ffffff;
            width: 32px;
            height: 32px;
        }
        .navbar-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.05em;
            line-height: 1.2;
        }
        .navbar-title span {
            display: block;
            font-size: 0.68rem;
            font-weight: 400;
            color: #8ab4e8;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-ghost {
            padding: 0.5rem 1.25rem;
            color: #c8d8f0;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
            transition: color .2s, background .2s;
        }
        .btn-ghost:hover { color: #fff; background: rgba(255,255,255,0.08); }
        .btn-primary {
            padding: 0.5rem 1.4rem;
            background: linear-gradient(135deg, #1a56b0, #0d3d8a);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            transition: opacity .2s, transform .1s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }

        /* ── Hero ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 6rem 2rem 4rem;
            text-align: center;
        }

        /* Fondo geométrico decorativo */
        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(26,86,176,0.18) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 10% 80%, rgba(192,57,43,0.10) 0%, transparent 60%);
        }
        .deco { position: absolute; border-radius: 50%; opacity: 0.07; }
        .deco-1 { width: 600px; height: 600px; border: 1.5px solid #2e86de; top: -150px; left: -150px; }
        .deco-2 { width: 400px; height: 400px; border: 1.5px solid #c0392b; top: -50px; left: -50px; }
        .deco-3 { width: 700px; height: 700px; border: 1.5px solid #2e86de; bottom: -250px; right: -200px; }
        .deco-sq {
            position: absolute;
            width: 160px; height: 160px;
            background: #1a56b0;
            opacity: 0.08;
            bottom: 120px; right: 100px;
            transform: rotate(20deg);
        }

        .hero-content { position: relative; z-index: 1; max-width: 720px; }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(26,86,176,0.2);
            border: 1px solid rgba(26,86,176,0.4);
            color: #8ab4e8;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            margin-bottom: 1.75rem;
        }
        .badge-dot {
            width: 6px; height: 6px;
            background: #1a56b0;
            border-radius: 50%;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
        }
        .hero h1 .highlight {
            color: transparent;
            background: linear-gradient(90deg, #2e86de, #1a56b0);
            -webkit-background-clip: text;
            background-clip: text;
        }
        .accent-bar {
            width: 48px; height: 4px;
            background: linear-gradient(90deg, #c0392b, #e74c3c);
            border-radius: 2px;
            margin: 1.25rem auto;
        }
        .hero p {
            font-size: 1.05rem;
            color: #8ab4e8;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            padding: 0.85rem 2.25rem;
            background: linear-gradient(135deg, #1a56b0, #0d3d8a);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 20px rgba(26,86,176,0.35);
        }
        .btn-hero-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26,86,176,0.45);
        }
        .btn-hero-ghost {
            padding: 0.85rem 2.25rem;
            background: transparent;
            color: #c8d8f0;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            border: 1.5px solid rgba(255,255,255,0.15);
            transition: border-color .2s, color .2s, background .2s;
        }
        .btn-hero-ghost:hover {
            border-color: rgba(255,255,255,0.35);
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        /* ── Stats strip ── */
        .stats-strip {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            flex-wrap: wrap;
        }
        .stat { text-align: center; }
        .stat-num {
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #6b8fc4;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 0.2rem;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.1);
            align-self: stretch;
        }

        /* ── Features ── */
        .features {
            padding: 5rem 2rem;
            background: #0a1628;
            position: relative;
        }
        .section-label {
            text-align: center;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #1a56b0;
            margin-bottom: 0.75rem;
        }
        .section-title {
            text-align: center;
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }
        .section-sub {
            text-align: center;
            color: #6b8fc4;
            font-size: 0.95rem;
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }
        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 2rem;
            transition: border-color .2s, background .2s, transform .2s;
        }
        .feature-card:hover {
            border-color: rgba(26,86,176,0.4);
            background: rgba(26,86,176,0.07);
            transform: translateY(-3px);
        }
        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            background: rgba(26,86,176,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        .feature-icon svg {
            width: 22px; height: 22px;
            stroke: #2e86de;
            fill: none;
            stroke-width: 1.5;
        }
        .feature-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 0.875rem;
            color: #6b8fc4;
            line-height: 1.65;
        }

        /* ── Footer ── */
        .footer {
            background: #060e1d;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-left {
            font-size: 0.8rem;
            color: #4a6fa5;
        }
        .footer-right {
            font-size: 0.75rem;
            color: #304060;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
                <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z"/>
            </svg>
            <div class="navbar-title">
                Sistema Diplomas QR
                <span>Universidad Politécnica Gómez Palacio</span>
            </div>
        </div>
        <div class="navbar-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Iniciar sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Registrarse</a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="deco deco-1"></div>
        <div class="deco deco-2"></div>
        <div class="deco deco-3"></div>
        <div class="deco-sq"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <div class="badge-dot"></div>
                Sistema Institucional
            </div>

            <h1>
                Diplomas digitales con<br>
                <span class="highlight">verificación QR</span>
            </h1>

            <div class="accent-bar"></div>

            <p>
                Plataforma oficial de la Universidad Politécnica Gómez Palacio<br>
                para la emisión, gestión y validación de diplomas con código QR.
            </p>

            <div class="hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero-primary">Ir al Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-primary">Iniciar sesión →</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-hero-ghost">Crear cuenta</a>
                    @endif
                @endauth
            </div>

            <div class="stats-strip">
                <div class="stat">
                    <div class="stat-num">QR</div>
                    <div class="stat-label">Verificación digital</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Institucional</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">UPGP</div>
                    <div class="stat-label">Gómez Palacio</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <p class="section-label">¿Qué puedes hacer?</p>
        <h2 class="section-title">Funcionalidades del sistema</h2>
        <p class="section-sub">Todo lo que necesitas para gestionar diplomas de forma digital y segura</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                    </svg>
                </div>
                <h3>Emisión de diplomas</h3>
                <p>Genera diplomas digitales de forma rápida y sencilla con los datos del participante y el evento.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                    </svg>
                </div>
                <h3>Código QR único</h3>
                <p>Cada diploma incluye un QR exclusivo que permite validar su autenticidad de forma instantánea.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <h3>Verificación segura</h3>
                <p>Sistema de validación institucional que garantiza la autenticidad de cada documento emitido.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                </div>
                <h3>Descarga en PDF</h3>
                <p>Los participantes pueden descargar su diploma en formato PDF listo para imprimir o compartir.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-left">
            © {{ date('Y') }} Universidad Politécnica Gómez Palacio — Todos los derechos reservados
        </div>
        <div class="footer-right">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} &nbsp;·&nbsp; PHP v{{ PHP_VERSION }}
        </div>
    </footer>

</body>
</html>
