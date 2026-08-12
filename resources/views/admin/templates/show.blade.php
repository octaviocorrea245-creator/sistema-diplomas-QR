<x-app-layout>
    <x-slot name="header">
        <h2>{{ $template->nombre }}</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:260px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding:1.5rem; }
        .card-title { font-size:0.7rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.6rem; border-bottom:1px solid #F0F4FA; }

        .info-row { display:flex; gap:8px; margin-bottom:0.8rem; font-size:0.875rem; align-items:flex-start; }
        .info-key { color:#64748b; min-width:90px; flex-shrink:0; font-size:0.82rem; }
        .info-val { color:#1E293B; font-weight:500; }

        .badge { display:inline-flex; align-items:center; gap:4px; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .badge-green { background:#F0FDF4; color:#16A34A; }
        .badge-gray  { background:#F1F5F9; color:#94A3B8; }
        .badge-blue  { background:#EFF6FF; color:#1D4ED8; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:6px;
            background:var(--brand); color:#fff; border:none;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-outline {
            display:inline-flex; align-items:center; gap:6px;
            background:#fff; color:#475569; border:1px solid #DDE3EF;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-outline:hover { background:#F7F9FC; color:#475569; }
        .btn-danger {
            display:inline-flex; align-items:center; gap:6px;
            background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600;
            cursor:pointer; transition:background 0.1s;
        }
        .btn-danger:hover { background:#FEE2E2; }

        .preview-wrapper {
            position:relative; overflow:hidden; border-radius:10px;
            border:1px solid #E8EDF4; background:#F8FAFC; cursor:pointer;
            aspect-ratio: {{ $template->canvas_width }} / {{ $template->canvas_height }};
        }
        .preview-overlay {
            position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
            opacity:0; transition:opacity 0.2s; background:rgba(0,0,0,0.35); border-radius:10px;
        }
        .preview-wrapper:hover .preview-overlay { opacity:1; }
        .overlay-btn {
            background:#fff; color:#1E293B; border:none; padding:0.5rem 1.2rem;
            border-radius:8px; font-size:0.8rem; font-weight:600; cursor:pointer;
            text-decoration:none; box-shadow:0 2px 12px rgba(0,0,0,0.2);
        }

        .data-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
        .data-table thead th { background:#F7F9FC; border-bottom:1px solid #E8EDF4;
            padding:0.65rem 1rem; text-align:left; font-size:0.7rem; font-weight:600;
            color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; }
        .data-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table tbody tr:hover { background:#F7F9FC; }
        .data-table td { padding:0.65rem 1rem; color:#334155; }

        .tipo-badge {
            display:inline-flex; align-items:center; padding:0.15rem 0.5rem;
            border-radius:6px; font-size:0.7rem; font-weight:600;
        }
        .tipo-text     { background:#EFF6FF; color:#1D4ED8; }
        .tipo-variable { background:#F0FDF4; color:#16A34A; }
        .tipo-qr       { background:#FFF7ED; color:#EA580C; }
        .tipo-image    { background:#F5F3FF; color:#7C3AED; }
        .tipo-rect     { background:#F1F5F9; color:#475569; }
        .tipo-line     { background:#FEF2F2; color:#DC2626; }

        .stat-pill {
            display:inline-flex; flex-direction:column; align-items:center;
            background:#F7F9FC; border:1px solid #E8EDF4; border-radius:10px;
            padding:0.75rem 1.1rem; min-width:80px;
        }
        .stat-num { font-size:1.4rem; font-weight:800; color:var(--brand); line-height:1; }
        .stat-lbl { font-size:0.65rem; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:0.07em; margin-top:3px; }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.templates.index') }}">Plantillas</a>
        <span class="breadcrumb-sep">/</span>
        <span>{{ $template->nombre }}</span>
    </div>

    <div class="page-grid">

        {{-- Panel izquierdo: info + acciones --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Info card --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Información</p>

                    <div class="info-row">
                        <span class="info-key">Curso</span>
                        <span class="info-val">{{ $template->curso->nombre ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tamaño</span>
                        <span class="info-val">{{ $template->canvas_width }} × {{ $template->canvas_height }} px</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Fondo</span>
                        <span>
                            @if($template->background_image)
                                <span class="badge badge-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Subido
                                </span>
                            @else
                                <span class="badge badge-gray">Sin fondo</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Creado</span>
                        <span class="info-val" style="color:#64748b;">{{ $template->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div style="display:flex;gap:0.5rem;margin-top:0.5rem;">
                        <div class="stat-pill">
                            <span class="stat-num">{{ $template->elements->count() }}</span>
                            <span class="stat-lbl">Elementos</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Acciones</p>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <a href="{{ route('admin.templates.editor', $template) }}" class="btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/>
                            </svg>
                            Diseñar plantilla
                        </a>
                        <a href="{{ route('admin.templates.edit', $template) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Editar datos
                        </a>
                        <a href="{{ route('templates.preview', $template) }}" target="_blank" class="btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Vista previa
                        </a>
                        @if($template->background_image)
                            <form action="{{ route('admin.templates.remove-background', $template) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-outline" style="width:100%;color:#DC2626;border-color:#FECACA;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Quitar fondo
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.templates.destroy', $template) }}" method="POST"
                              onsubmit="return confirmAction(event, '¿Eliminar esta plantilla y todos sus elementos?')" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="width:100%;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Eliminar plantilla
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Preview del fondo --}}
            @if($template->background_image)
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Vista previa del fondo</p>
                        <div class="preview-wrapper">
                            <img src="{{ asset($template->background_image) }}"
                                 style="width:100%;height:100%;object-fit:cover;display:block;">
                            <a href="{{ route('admin.templates.editor', $template) }}" class="preview-overlay">
                                <span class="overlay-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:4px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/>
                                    </svg>
                                    Editar plantilla
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Elementos --}}
            <div class="card">
                <div class="card-body" style="padding-bottom:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;padding-bottom:0.6rem;border-bottom:1px solid #F0F4FA;">
                        <span style="font-size:0.7rem;font-weight:700;color:#4A6585;letter-spacing:0.08em;text-transform:uppercase;">
                            Elementos ({{ $template->elements->count() }})
                        </span>
                        <a href="{{ route('admin.templates.editor', $template) }}" class="btn-primary" style="padding:0.35rem 0.8rem;font-size:0.72rem;">
                            Abrir editor
                        </a>
                    </div>
                </div>

                @if($template->elements->isEmpty())
                    <div style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" style="width:40px;height:40px;margin:0 auto 0.75rem;display:block;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
                        </svg>
                        <p style="font-size:0.875rem;">Sin elementos. Usa el editor para agregarlos.</p>
                    </div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Variable</th>
                                <th>Posición</th>
                                <th>Tamaño</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($template->elements as $el)
                                <tr>
                                    <td style="color:#94A3B8;font-size:0.78rem;">{{ $el->orden }}</td>
                                    <td>
                                        @php
                                            $tipoCls = match($el->tipo) {
                                                'text'     => 'tipo-text',
                                                'variable' => 'tipo-variable',
                                                'qr'       => 'tipo-qr',
                                                'image'    => 'tipo-image',
                                                'rect'     => 'tipo-rect',
                                                'line'     => 'tipo-line',
                                                default    => 'tipo-rect',
                                            };
                                        @endphp
                                        <span class="tipo-badge {{ $tipoCls }}">{{ $el->tipo }}</span>
                                    </td>
                                    <td style="font-size:0.78rem;color:#64748b;">{{ $el->variable ?? '—' }}</td>
                                    <td style="font-family:monospace;font-size:0.78rem;color:#64748b;">{{ round($el->x) }}, {{ round($el->y) }}</td>
                                    <td style="font-family:monospace;font-size:0.78rem;color:#64748b;">{{ round($el->width) }} × {{ round($el->height) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>

    <script>
        (function() {
            var wrapper = document.querySelector('.preview-wrapper');
            var img = wrapper ? wrapper.querySelector('img') : null;
            if (!wrapper || !img) return;
            var cw = {{ $template->canvas_width ?? 1920 }};
            var ch = {{ $template->canvas_height ?? 1358 }};
            function setAspect() {
                wrapper.style.aspectRatio = cw + ' / ' + ch;
            }
            setAspect();
            window.addEventListener('resize', setAspect);
        })();
    </script>

</x-app-layout>
