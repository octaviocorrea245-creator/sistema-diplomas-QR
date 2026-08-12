{{-- resources/views/admin/alumnos/import.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>Importar alumnos (CSV)</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:1fr 300px; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .section-label { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .drop-zone {
            border:2px dashed #CBD5E1; border-radius:12px; padding:2.5rem 1rem;
            text-align:center; cursor:pointer; transition:border-color 0.2s, background 0.2s;
            background:#F8FAFC; position:relative;
        }
        .drop-zone.drag-over, .drop-zone:hover { border-color:var(--brand); background:var(--brand-bg); }
        .drop-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .drop-icon { width:40px; height:40px; margin:0 auto 0.75rem; color:#94A3B8; }
        .drop-title { font-size:0.9rem; font-weight:600; color:#334155; margin-bottom:4px; }
        .drop-hint  { font-size:0.78rem; color:#94A3B8; }
        .file-selected { font-size:0.82rem; font-weight:600; color:var(--brand); margin-top:0.75rem; display:none; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.65rem 1.4rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.65rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }
        .btn-download {
            display:inline-flex; align-items:center; gap:7px;
            background:#F1F5F9; color:#334155; border:1px solid #DDE3EF;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.82rem; font-weight:600;
            text-decoration:none; transition:background 0.1s;
        }
        .btn-download:hover { background:#E8EDF4; color:#334155; }

        .info-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .info-card-header { background:var(--brand-gradient); padding:1.1rem 1.25rem; }
        .info-card-body { padding:1.25rem; }

        .col-row { margin-bottom:0.85rem; padding-bottom:0.85rem; border-bottom:1px solid #F0F4FA; }
        .col-row:last-child { margin-bottom:0; padding-bottom:0; border-bottom:none; }
        .col-name { font-family:monospace; font-size:0.82rem; font-weight:700; color:var(--brand); }
        .col-req  { font-size:0.68rem; font-weight:700; color:#DC2626; margin-left:4px; }
        .col-desc { font-size:0.78rem; color:#64748b; margin-top:2px; }
        .col-ex   { font-size:0.73rem; color:#94A3B8; font-style:italic; margin-top:2px; }

        .notice { background:var(--brand-bg); border:1px solid var(--brand-alpha);
            border-radius:10px; padding:0.9rem 1.1rem; font-size:0.82rem; color:#334155; margin-top:1rem; }
        .alert-error { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.alumnos.index') }}">Alumnos</a>
        <span class="breadcrumb-sep">/</span>
        <span>Importar CSV</span>
    </div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="page-grid">

        <div class="form-card">
            <div class="section-label">Subir archivo</div>

            <form method="POST" action="{{ route('admin.alumnos.importar.store') }}"
                  enctype="multipart/form-data" id="import-form">
                @csrf

                <div class="drop-zone" id="drop-zone">
                    <input type="file" name="archivo" accept=".csv,.txt" id="csv-input">
                    <svg class="drop-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15"/>
                    </svg>
                    <div class="drop-title">Arrastra el CSV aquí o haz clic para seleccionar</div>
                    <div class="drop-hint">Formato .csv · Máx. 4 MB</div>
                    <div class="file-selected" id="file-name"></div>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.25rem;align-items:center;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary" id="submit-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        Importar alumnos
                    </button>
                    <a href="{{ route('admin.alumnos.index') }}" class="btn-cancel">Cancelar</a>
                    <a href="{{ route('admin.alumnos.plantilla') }}" class="btn-download" style="margin-left:auto;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Descargar plantilla
                    </a>
                </div>
            </form>

            <div class="notice">
                Los alumnos nuevos reciben una <strong>contraseña temporal aleatoria</strong>. Compártela con ellos o edita su perfil para cambiarla.
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.6);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:4px;">Formato CSV</div>
                    <div style="font-size:1rem;font-weight:700;color:#fff;">Columnas esperadas</div>
                </div>
                <div class="info-card-body">
                    <div class="col-row">
                        <div><span class="col-name">nombre_completo</span><span class="col-req">*</span></div>
                        <div class="col-desc">Nombre completo del alumno.</div>
                        <div class="col-ex">Ej: Juan García López</div>
                    </div>
                    <div class="col-row">
                        <div><span class="col-name">username</span></div>
                        <div class="col-desc">Identificador de acceso. Si se deja vacío, se genera uno automáticamente a partir del nombre.</div>
                        <div class="col-ex">Ej: jgarcia</div>
                    </div>

                    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:0.75rem;margin-top:0.5rem;font-size:0.78rem;color:#15803D;">
                        <strong>Alumno ya existente:</strong> si el nombre o username ya está en tu departamento, esa fila se omite (no se duplica).
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const input  = document.getElementById('csv-input');
        const zone   = document.getElementById('drop-zone');
        const nameEl = document.getElementById('file-name');

        input.addEventListener('change', () => {
            if (input.files[0]) { nameEl.textContent = '📄 ' + input.files[0].name; nameEl.style.display = 'block'; }
        });
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            if (e.dataTransfer.files[0]) {
                const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]); input.files = dt.files;
                nameEl.textContent = '📄 ' + e.dataTransfer.files[0].name; nameEl.style.display = 'block';
            }
        });
        document.getElementById('import-form').addEventListener('submit', () => {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;animation:spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Procesando…`;
        });
    </script>
    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>

</x-app-layout>
