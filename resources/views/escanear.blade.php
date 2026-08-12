<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escanear QR — Sistema Diplomas UPGP</title>
    @vite('resources/css/app.css')
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f4f6fb;
        }
        .header {
            background: #060e1d;
            color: #fff;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header a { color: #6b9fd4; text-decoration: none; font-size: 0.85rem; }
        .header a:hover { color: #fff; }
        .scanner-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
        }
        .scanner-box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 1.5rem;
        }
        #qr-reader {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
        #qr-reader video { border-radius: 10px; }
        #qr-reader img { border-radius: 10px; }
        .status-text {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
        }
        .input-row {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .input-row input {
            flex: 1;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.85rem;
            font-family: inherit;
            outline: none;
        }
        .input-row input:focus { border-color: #1a56b0; box-shadow: 0 0 0 3px rgba(26,86,176,0.1); }
        .input-row button {
            background: #1a56b0;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }
        .input-row button:hover { background: #0d3d8a; }
        .error-text {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: #dc2626;
            text-align: center;
            display: none;
        }
        .error-text a { color: #1a56b0; font-weight: 600; }
        .footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <span style="font-size:0.8rem;font-weight:600;">Sistema Diplomas QR — UPGP</span>
        <a href="{{ route('login') }}">Iniciar sesión</a>
    </div>

    <div class="scanner-wrap">
        <div class="scanner-box">
            <h1 style="font-size:1.1rem;font-weight:700;color:#1e293b;text-align:center;margin-bottom:0.25rem;">
                Escanear código QR
            </h1>
            <p style="font-size:0.82rem;color:#64748b;text-align:center;margin-bottom:1.25rem;">
                Apunta la cámara al código QR del diploma
            </p>

            <div id="qr-reader"></div>

            <div class="status-text" id="scanner-status">Iniciando cámara...</div>
            <div class="error-text" id="scanner-error"></div>

            <div class="input-row">
                <input type="text" id="manual-token" placeholder="O pega el token del QR aquí">
                <button onclick="verificarToken()">Verificar</button>
            </div>
        </div>

        <div style="margin-top:1rem;text-align:center;">
            <a href="{{ route('verificar', '') }}" style="font-size:0.8rem;color:#94a3b8;text-decoration:none;display:none;" id="direct-link"></a>
        </div>
    </div>

    <div class="footer">
        Universidad Politécnica Gómez Palacio &middot; Sistema de Verificación de Diplomas
    </div>

    @vite('resources/js/app.js')
    <script>
    function verificarToken() {
        var token = document.getElementById('manual-token').value.trim();
        if (token) {
            window.location.href = '{{ url('/verificar') }}/' + encodeURIComponent(token);
        }
    }

    function iniciarScanner() {
        var statusEl = document.getElementById('scanner-status');
        var errorEl = document.getElementById('scanner-error');

        if (typeof Html5Qrcode === 'undefined') {
            statusEl.textContent = 'Cargando librería...';
            setTimeout(iniciarScanner, 300);
            return;
        }

        var scanner = new Html5Qrcode('qr-reader');

        scanner.start(
            { facingMode: 'environment' },
            { fps: 15, qrbox: { width: 280, height: 280 } },
            function (decodedText) {
                var token = decodedText;
                try {
                    var url = new URL(decodedText);
                    var parts = url.pathname.split('/');
                    token = parts[parts.length - 1];
                } catch (e) {}

                statusEl.innerHTML = '¡QR detectado! Redirigiendo...';
                scanner.stop().catch(function(){});
                setTimeout(function() {
                    window.location.href = '{{ url('/verificar') }}/' + encodeURIComponent(token);
                }, 500);
            },
            function () {}
        ).then(function () {
            statusEl.textContent = 'Cámara activada. Apunta al código QR.';
            errorEl.style.display = 'none';
        }).catch(function (err) {
            statusEl.textContent = '';
            errorEl.style.display = 'block';
            var msg = String(err);
            if (msg.includes('NotAllowedError') || msg.includes('Permission')) {
                errorEl.innerHTML = 'Permiso de cámara denegado. '
                    + '<a href="#" onclick="document.getElementById(\'manual-token\').focus();return false;">Usa el campo de texto</a> '
                    + 'para pegar el token manualmente.';
            } else if (msg.includes('NotFoundError')) {
                errorEl.innerHTML = 'No se encontró una cámara en este dispositivo. '
                    + 'Usa el campo de texto para pegar el token.';
            } else {
                errorEl.innerHTML = 'Error al iniciar la cámara: ' + msg
                    + '<br><a href="#" onclick="document.getElementById(\'manual-token\').focus();return false;">Usar campo de texto</a>';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', iniciarScanner);
    </script>
</body>
</html>