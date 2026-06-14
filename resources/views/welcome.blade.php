<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Diplomas QR — UPGP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0d1b35;
            color: #ffffff;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

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
        .navbar-brand svg { fill: #ffffff; width: 32px; height: 32px; }
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
        .navbar-actions { display: flex; align-items: center; gap: 1rem; }
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
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .scanner-section {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem;
            max-width: 420px;
            margin: 2rem auto 0;
        }
        .scanner-section h3 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #8ab4e8;
            margin-bottom: 1rem;
        }
        #qr-reader {
            border: none !important;
            border-radius: 10px;
            overflow: hidden;
        }
        #qr-reader video { border-radius: 10px; }
        #qr-reader__scan_region { min-height: 250px; }
        #qr-reader__dashboard_section_csr span {
            color: #8ab4e8 !important;
            font-size: 0.85rem !important;
        }
        #qr-reader__dashboard_section_csr button {
            background: #1a56b0 !important;
            border: none !important;
            color: #fff !important;
            padding: 0.4rem 1rem !important;
            border-radius: 6px !important;
            font-size: 0.8rem !important;
        }
        .qr-result {
            margin-top: 1rem;
            padding: 0.75rem;
            background: rgba(26,86,176,0.12);
            border-radius: 8px;
            font-size: 0.82rem;
            color: #c8d8f0;
            word-break: break-all;
            display: none;
        }

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
        .footer-left { font-size: 0.8rem; color: #4a6fa5; }
        .footer-right { font-size: 0.75rem; color: #304060; letter-spacing: 0.05em; }
    </style>
</head>
<body>

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
                <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-bg"></div>
        <div class="deco deco-1"></div>
        <div class="deco deco-2"></div>
        <div class="deco deco-3"></div>
        <div class="deco-sq"></div>

        <div class="hero-content">
            <h1>
                Escanea tu código QR<br>
                <span class="highlight">para ver tu diploma</span>
            </h1>

            <div class="accent-bar"></div>

            <p>
                Apunta la cámara al código QR de tu diploma<br>
                para verificar y descargar tu documento.
            </p>

            <div class="scanner-section">
                <h3>📷 Escanea el código QR</h3>
                <div id="qr-reader"></div>
                <div id="qr-result" class="qr-result"></div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-left">
            © {{ date('Y') }} Universidad Politécnica Gómez Palacio — Todos los derechos reservados
        </div>
        <div class="footer-right">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} &nbsp;·&nbsp; PHP v{{ PHP_VERSION }}
        </div>
    </footer>

    <script>
        const qrReader = new Html5Qrcode('qr-reader');

        qrReader.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 250 },
            onScanSuccess
        ).catch(err => {
            document.getElementById('qr-result').style.display = 'block';
            document.getElementById('qr-result').textContent = 'No se pudo acceder a la cámara: ' + err;
        });

        function onScanSuccess(decodedText) {
            qrReader.stop();
            window.location.href = decodedText;
        }
    </script>

</body>
</html>
