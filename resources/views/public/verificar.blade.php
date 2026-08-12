<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Diploma — UPGP</title>
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container { max-width: 960px; margin: 0 auto; }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .badge {
            display: inline-block;
            background: #e0e7ff;
            color: #4338ca;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }
        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .folio { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; }
        .section { margin-bottom: 1.25rem; }
        .section-title {
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.5rem;
        }
        .info-row {
            display: flex; justify-content: space-between;
            padding: 0.4rem 0; border-bottom: 1px solid #f3f4f6; font-size: 0.9rem;
        }
        .info-row .label { color: #6b7280; }
        .info-row .value { font-weight: 500; }
        .btn-download {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.85rem; background: #1a56b0; color: #fff;
            border: none; border-radius: 10px; font-size: 0.95rem; font-weight: 600;
            cursor: pointer; transition: opacity .2s; margin-top: 1.5rem;
        }
        .btn-download:hover { opacity: 0.9; }
        a.btn-download { text-decoration: none; }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.6rem 1.2rem; background: #fff; color: #374151;
            border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.85rem; font-weight: 500;
            cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .btn-secondary:hover { background: #f9fafb; }
        .footer { text-align: center; margin-top: 1.5rem; font-size: 0.75rem; color: #9ca3af; }

        .firma-banner {
            display: flex; align-items: flex-start; gap: 12px;
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;
            padding: 1rem 1.1rem; margin-bottom: 1.25rem;
        }
        .firma-banner-icon {
            width: 38px; height: 38px; border-radius: 50%; background: #dcfce7;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .firma-banner-title { font-size: 0.875rem; font-weight: 700; color: #15803d; margin-bottom: 0.2rem; }
        .firma-banner-detail { font-size: 0.78rem; color: #166534; line-height: 1.5; }
        .firma-banner-mono { font-family: monospace; font-size: 0.72rem; color: #15803d; word-break: break-all; }

        .no-firma-banner {
            display: flex; align-items: center; gap: 10px;
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 0.85rem 1.1rem; margin-bottom: 1.25rem;
            font-size: 0.8rem; color: #64748b;
        }

        .diploma-visual {
            width: 100%; border-radius: 8px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem;
        }
        .actions { display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; }

        .timer-bar {
            height: 4px; background: #e5e7eb; border-radius: 2px;
            margin-bottom: 1rem; overflow: hidden;
        }
        .timer-bar-fill {
            height: 100%; background: #1a56b0; border-radius: 2px;
            transition: width 1s linear;
        }
        .timer-text {
            font-size: 0.75rem; color: #6b7280; text-align: center;
            margin-bottom: 1rem;
        }
        .expired-msg {
            text-align: center; padding: 2rem 1rem;
        }
        .expired-msg svg { width: 48px; height: 48px; color: #9ca3af; margin-bottom: 1rem; }
        .expired-msg h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .expired-msg p { font-size: 0.85rem; color: #6b7280; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="badge">Diploma Verificado</div>

            <h1>{{ $diploma->alumno->full_name }}</h1>
            <p class="folio">Folio: {{ $diploma->folio }}</p>

            {{-- Timer bar --}}
            <div id="timerBar" class="timer-bar">
                <div id="timerFill" class="timer-bar-fill" style="width:100%"></div>
            </div>
            <p id="timerText" class="timer-text">Sesión de visualización expira en <span id="timerCount">10:00</span></p>

            {{-- Diploma visual --}}
            <div id="diplomaContent">
                @if($diplomaHtml)
                    <div id="diplomaVisual" class="diploma-visual" style="overflow:hidden; background:#fff; border:1px solid #e5e7eb; position:relative;">
                        <div id="diplomaInner" style="transform-origin:top left; width:{{ $diploma->template?->canvas_width ?? 800 }}px;">
                            {!! $diplomaHtml !!}
                        </div>
                    </div>
                @endif

                <div class="section">
                    <p class="section-title">Curso</p>
                    <div class="info-row">
                        <span class="label">Nombre</span>
                        <span class="value">{{ $diploma->curso->nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Horas</span>
                        <span class="value">{{ $diploma->curso->horas ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Fechas</span>
                        <span class="value">{{ $diploma->curso->fecha_inicio?->format('d/m/Y') }} — {{ $diploma->curso->fecha_fin?->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Departamento</span>
                        <span class="value">{{ $diploma->curso->departamento->name ?? '—' }}</span>
                    </div>
                </div>

                <div class="section">
                    <p class="section-title">Diploma</p>
                    <div class="info-row">
                        <span class="label">Folio</span>
                        <span class="value">{{ $diploma->folio }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Fecha de emisión</span>
                        <span class="value">{{ $diploma->fecha_emision?->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Emitido por</span>
                        <span class="value">{{ $diploma->emisor?->full_name ?? '—' }}</span>
                    </div>
                </div>

                {{-- ─── Sección de firma digital ─── --}}
                @if($diploma->estaFirmado() && $diploma->firmante)
                    <div class="firma-banner">
                        <div class="firma-banner-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" style="width:20px;height:20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="firma-banner-title">Firmado digitalmente con e.firma SAT</div>
                            <div class="firma-banner-detail">
                                <strong>{{ $diploma->firmante->nombre }}</strong> — {{ $diploma->firmante->cargo }}<br>
                                @if($diploma->firmante->rfc)
                                    RFC: <span class="firma-banner-mono">{{ $diploma->firmante->rfc }}</span><br>
                                @endif
                                @if($diploma->cert_serie_usada)
                                    Certificado: <span class="firma-banner-mono">{{ $diploma->cert_serie_usada }}</span><br>
                                @endif
                                @if($diploma->firmado_en)
                                    Fecha de firma: {{ $diploma->firmado_en->format('d/m/Y \a \l\a\s H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="no-firma-banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Este diploma no cuenta con firma digital e.firma SAT.
                    </div>
                @endif

                <div class="actions">
                    @if($diploma->template && $diploma->template->elements->isNotEmpty())
                        <a href="{{ route('verificar.pdf', $diploma->token_qr) }}" class="btn-download" style="margin-top:0;width:auto;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Descargar Diploma (PDF)
                        </a>
                    @endif
                </div>
            </div>

            {{-- Expired message --}}
            <div id="expiredMsg" class="expired-msg hidden">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <h3>Sesión de visualización expirada</h3>
                <p>Escanea el código QR del diploma nuevamente para renovar la sesión de 10 minutos.</p>
            </div>

            <p class="footer">Universidad Politécnica Gómez Palacio</p>
        </div>
    </div>

    <script>
        var tiempoRestante = {{ $tiempoRestante }};
        var canvasW = {{ $diploma->template?->canvas_width ?? 800 }};
        var canvasH = {{ $diploma->template?->canvas_height ?? 600 }};

        function actualizarTimer() {
            if (tiempoRestante <= 0) {
                document.getElementById('diplomaContent').classList.add('hidden');
                document.getElementById('expiredMsg').classList.remove('hidden');
                document.getElementById('timerBar').classList.add('hidden');
                document.getElementById('timerText').classList.add('hidden');
                return;
            }

            var mins = Math.floor(tiempoRestante / 60);
            var secs = Math.floor(tiempoRestante % 60);
            document.getElementById('timerCount').textContent =
                String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

            var pct = (tiempoRestante / 600) * 100;
            document.getElementById('timerFill').style.width = pct + '%';

            tiempoRestante--;
        }

        function scaleDiploma() {
            var visual = document.getElementById('diplomaVisual');
            var inner = document.getElementById('diplomaInner');
            if (!visual || !inner) return;
            var scale = (visual.clientWidth - 2) / canvasW;
            if (scale > 1) scale = 1;
            inner.style.transform = 'scale(' + scale + ')';
            visual.style.height = Math.ceil(canvasH * scale) + 'px';
        }

        actualizarTimer();
        setInterval(actualizarTimer, 1000);
        scaleDiploma();
        window.addEventListener('resize', scaleDiploma);
    </script>
    <div style="text-align:center;padding:1rem;background:#f8fafc;border-top:1px solid #e2e8f0;">
        <a href="{{ route('escanear') }}"
           style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:#1a56b0;text-decoration:none;font-weight:500;padding:0.5rem 1rem;border-radius:8px;border:1px solid #bfdbfe;background:#fff;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/>
            </svg>
            Escanear otro código QR
        </a>
    </div>
</body>
</html>