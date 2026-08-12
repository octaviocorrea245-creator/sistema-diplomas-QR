<x-app-layout>
    <x-slot name="header">
        <h2>Registrar firmante</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; transition:color 0.1s; }
        .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; max-width:580px; }
        .form-group { margin-bottom:1.25rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-hint  { font-size:0.75rem; color:#94A3B8; margin-top:0.25rem; }
        .form-input {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s; box-sizing:border-box;
        }
        .form-input:focus { border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        .file-zone {
            border:2px dashed #DDE3EF; border-radius:10px; padding:1.25rem;
            text-align:center; cursor:pointer; transition:border-color 0.15s, background 0.15s;
            position:relative;
        }
        .file-zone:hover { border-color:var(--brand); background:#F8FAFF; }
        .file-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .file-zone-icon { color:#94A3B8; margin-bottom:0.5rem; }
        .file-zone-label { font-size:0.82rem; color:#64748b; font-weight:500; }
        .file-zone-ext   { font-size:0.72rem; color:#94A3B8; margin-top:0.2rem; }
        .file-zone-name  { font-size:0.82rem; color:var(--brand); font-weight:600; margin-top:0.5rem; display:none; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.6rem 1.3rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:0.6rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        .section-title { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .security-note {
            background:#F0FDF4; border:1px solid #BBF7D0; border-radius:9px;
            padding:0.75rem 1rem; font-size:0.78rem; color:#15803D; margin-top:1.5rem;
            display:flex; align-items:flex-start; gap:8px;
        }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.firmantes.index') }}">Firmantes</a>
        <span class="breadcrumb-sep">/</span>
        <span>Registrar</span>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.firmantes.store') }}" enctype="multipart/form-data">
            @csrf

            <p class="section-title">Datos del firmante</p>

            <div class="form-group">
                <label class="form-label">Nombre completo <span style="color:#DC2626;">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="form-input" placeholder="Ej. Dr. Juan Pérez Hernández">
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Cargo <span style="color:#DC2626;">*</span></label>
                <input type="text" name="cargo" value="{{ old('cargo', 'Director de Programa Académico') }}"
                       class="form-input" placeholder="Cargo del firmante">
                @error('cargo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <p class="section-title">Archivos de e.firma SAT</p>

            <div class="form-group">
                <label class="form-label">Certificado (.cer) <span style="color:#DC2626;">*</span></label>
                <div class="file-zone" id="zone-cer">
                    <input type="file" name="cer_file" accept=".cer" onchange="showName(this,'zone-cer')">
                    <div class="file-zone-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:28px;height:28px;margin:0 auto;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div class="file-zone-label">Arrastra el .cer o haz clic</div>
                    <div class="file-zone-ext">Certificado público de e.firma SAT</div>
                    <div class="file-zone-name" id="name-cer"></div>
                </div>
                @error('cer_file') <p class="form-error">{{ $message }}</p> @enderror
                <p class="form-hint">El RFC, nombre y vigencia se extraen automáticamente del certificado.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Llave privada (.key) <span style="color:#DC2626;">*</span></label>
                <div class="file-zone" id="zone-key">
                    <input type="file" name="key_file" accept=".key" onchange="showName(this,'zone-key')">
                    <div class="file-zone-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:28px;height:28px;margin:0 auto;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                        </svg>
                    </div>
                    <div class="file-zone-label">Arrastra el .key o haz clic</div>
                    <div class="file-zone-ext">Llave privada cifrada de e.firma SAT</div>
                    <div class="file-zone-name" id="name-key"></div>
                </div>
                @error('key_file') <p class="form-error">{{ $message }}</p> @enderror
                <p class="form-hint">La contraseña del .key <strong>no</strong> se guarda aquí. Se solicita cada vez que se firma un diploma.</p>
            </div>

            <div class="security-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                Los archivos .cer y .key se almacenan en una carpeta privada del servidor, fuera del acceso web directo.
            </div>

            <div style="display:flex;gap:0.75rem;margin-top:1.75rem;">
                <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    Registrar firmante
                </button>
                <a href="{{ route('admin.firmantes.index') }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
    function showName(input, zoneId) {
        const nameEl = document.getElementById('name-' + zoneId.replace('zone-', ''));
        if (input.files.length > 0) {
            nameEl.textContent = '✓ ' + input.files[0].name;
            nameEl.style.display = 'block';
        }
    }
    </script>

</x-app-layout>
