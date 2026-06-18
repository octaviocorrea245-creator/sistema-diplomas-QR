<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Diploma — UPGP</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
        }
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
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.6rem 1.2rem; background: #fff; color: #374151;
            border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.85rem; font-weight: 500;
            cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .btn-secondary:hover { background: #f9fafb; }
        .footer { text-align: center; margin-top: 1.5rem; font-size: 0.75rem; color: #9ca3af; }

        .diploma-visual {
            width: 100%; border-radius: 8px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem;
        }
        .actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="badge">Diploma Verificado</div>

            <h1>{{ $diploma->alumno->full_name }}</h1>
            <p class="folio">Folio: {{ $diploma->folio }}</p>

            {{-- Diploma visual (con scroll si es muy grande) --}}
            @if($diplomaHtml)
                <div class="diploma-visual" style="overflow:auto; background:#fff; border:1px solid #e5e7eb; max-height:500px;">
                    <div style="width:{{ $diploma->template->canvas_width }}px; transform-origin:top left;">
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

            <div class="actions">
                <button class="btn-download" onclick="descargarPDF()" style="margin-top:0;width:auto;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Descargar PDF
                </button>
                @if($diploma->template && $diploma->template->elements->isNotEmpty())
                    <a href="{{ route('verificar.pdf', $diploma->token_qr) }}" class="btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Diseño original (PDF)
                    </a>
                @endif
            </div>

            <p class="footer">Universidad Politécnica Gómez Palacio</p>
        </div>
    </div>

    <script>
        function descargarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a4');

            const pageW = 297;
            const pageH = 210;

            doc.setDrawColor(26, 86, 176);
            doc.setLineWidth(2);
            doc.rect(10, 10, pageW - 20, pageH - 20);
            doc.setLineWidth(0.5);
            doc.rect(13, 13, pageW - 26, pageH - 26);

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(22);
            doc.setTextColor(26, 86, 176);
            doc.text('Diploma', pageW / 2, 45, { align: 'center' });

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(100, 116, 139);
            doc.text('Universidad Politécnica Gómez Palacio', pageW / 2, 55, { align: 'center' });

            doc.setDrawColor(26, 86, 176);
            doc.setLineWidth(0.5);
            doc.line(pageW / 2 - 40, 62, pageW / 2 + 40, 62);

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(26);
            doc.setTextColor(31, 41, 55);
            doc.text('{{ $diploma->alumno->full_name }}', pageW / 2, 82, { align: 'center' });

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(12);
            doc.setTextColor(75, 85, 99);
            doc.text('Por haber completado satisfactoriamente el curso de:', pageW / 2, 98, { align: 'center' });

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            doc.setTextColor(31, 41, 55);
            doc.text('{{ $diploma->curso->nombre }}', pageW / 2, 114, { align: 'center' });

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(100, 116, 139);
            doc.text('{{ $diploma->curso->horas ?? '' }} horas', pageW / 2, 126, { align: 'center' });

            const fechaInicio = '{{ $diploma->curso->fecha_inicio?->format('d/m/Y') ?? '—' }}';
            const fechaFin = '{{ $diploma->curso->fecha_fin?->format('d/m/Y') ?? '—' }}';
            doc.text('Del ' + fechaInicio + ' al ' + fechaFin, pageW / 2, 138, { align: 'center' });

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(9);
            doc.setTextColor(156, 163, 175);
            doc.text('Folio: {{ $diploma->folio }}', pageW / 2, pageH - 30, { align: 'center' });
            doc.text('Emitido el {{ $diploma->fecha_emision?->format('d/m/Y') ?? '—' }}', pageW / 2, pageH - 24, { align: 'center' });

            doc.save('diploma-{{ $diploma->folio }}.pdf');
        }
    </script>
</body>
</html>
