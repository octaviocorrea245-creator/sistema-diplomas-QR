<x-app-layout>
    <x-slot name="header">
        <h2>Editar firmante</h2>
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
            border:2px dashed #DDE3EF; border-radius:10px; padding:1rem;
            text-align:center; cursor:pointer; transition:border-color 0.15s, background 0.15s;
            position:relative;
        }
        .file-zone:hover { border-color:var(--brand); background:#F8FAFF; }
        .file-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .file-zone-label { font-size:0.82rem; color:#64748b; font-weight:500; }
        .file-zone-ext   { font-size:0.72rem; color:#94A3B8; margin-top:0.2rem; }
        .file-zone-name  { font-size:0.82rem; color:var(--brand); font-weight:600; margin-top:0.4rem; display:none; }

        .current-file {
            background:#F7F9FC; border:1px solid #E8EDF4; border-radius:8px;
            padding:0.5rem 0.85rem; font-size:0.78rem; color:#475569; margin-bottom:0.5rem;
            display:flex; align-items:center; gap:6px;
        }

        .cert-info-box {
            background:#F8FAFF; border:1px solid #BFDBFE; border-radius:10px;
            padding:1rem 1.1rem; margin-bottom:1.25rem;
        }
        .cert-info-row { display:flex; gap:8px; align-items:baseline; margin-bottom:0.35rem; font-size:0.82rem; }
        .cert-info-key { color:#4A6585; font-weight:600; min-width:120px; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; }
        .cert-info-val { color:#1E293B; font-family:monospace; font-size:0.82rem; }

        .section-title { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .toggle-group { display:flex; align-items:center; gap:10px; }
        .toggle-switch { position:relative; width:40px; height:22px; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider {
            position:absolute; cursor:pointer; inset:0; background:#E2E8F0;
            border-radius:22px; transition:background 0.2s;
        }
        .toggle-slider:before {
            content:''; position:absolute; height:16px; width:16px;
            left:3px; bottom:3px; background:#fff; border-radius:50%;
            transition:transform 0.2s; box-shadow:0 1px 3px rgba(0,0,0,0.2);
        }
        .toggle-switch input:checked + .toggle-slider { background:var(--brand); }
        .toggle-switch input:checked + .toggle-slider:before { transform:translateX(18px); }

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
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.firmantes.index') }}">Firmantes</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.firmantes.show', $firmante) }}">{{ $firmante->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Editar</span>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.firmantes.update', $firmante) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <p class="section-title">Datos del firmante</p>

            <div class="form-group">
                <label class="form-label">Nombre completo <span style="color:#DC2626;">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre', $firmante->nombre) }}"
                       class="form-input">
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Cargo <span style="color:#DC2626;">*</span></label>
                <input type="text" name="cargo" value="{{ old('cargo', $firmante->cargo) }}"
                       class="form-input">
                @error('cargo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Activo</label>
                <div class="toggle-group">
                    <label class="toggle-switch">
                        <input type="checkbox" name="activo" value="1" {{ $firmante->activo ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="font-size:0.82rem;color:#64748b;">El firmante puede firmar diplomas</span>
                </div>
            </div>

            <p class="section-title">Certificado actual</p>

            @if($firmante->rfc || $firmante->certificado_numero)
                <div class="cert-info-box">
                    @if($firmante->rfc)
                        <div class="cert-info-row">
                            <span class="cert-info-key">RFC</span>
                            <span class="cert-info-val">{{ $firmante->rfc }}</span>
                        </div>
                    @endif
                    @if($firmante->certificado_numero)
                        <div class="cert-info-row">
                            <span class="cert-info-key">No. serie</span>
                            <span class="cert-info-val" style="font-size:0.75rem;">{{ $firmante->certificado_numero }}</span>
                        </div>
                    @endif
                    @if($firmante->cert_valido_desde)
                        <div class="cert-info-row">
                            <span class="cert-info-key">Válido desde</span>
                            <span class="cert-info-val">{{ $firmante->cert_valido_desde->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    @if($firmante->cert_expira_en)
                        <div class="cert-info-row">
                            <span class="cert-info-key">Vence</span>
                            <span class="cert-info-val" style="color:{{ $firmante->certVigente() ? '#15803D' : '#DC2626' }};">
                                {{ $firmante->cert_expira_en->format('d/m/Y') }}
                                @if(!$firmante->certVigente()) <strong>(Vencido)</strong> @endif
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <p class="section-title">Reemplazar archivos (opcional)</p>
            <p class="form-hint" style="margin-bottom:1rem;">Deja vacío si no quieres cambiar los archivos actuales.</p>

            <div class="form-group">
                <label class="form-label">Nuevo certificado (.cer)</label>
                <div class="current-file">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;color:#94A3B8;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25"/>
                    </svg>
                    Archivo actual guardado
                </div>
                <div class="file-zone">
                    <input type="file" name="cer_file" accept=".cer" onchange="showName(this,'cer')">
                    <div class="file-zone-label">Subir nuevo .cer</div>
                    <div class="file-zone-ext">Solo si renovaste la e.firma</div>
                    <div class="file-zone-name" id="name-cer"></div>
                </div>
                @error('cer_file') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nueva llave privada (.key)</label>
                <div class="current-file">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;color:#94A3B8;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499"/>
                    </svg>
                    Archivo actual guardado
                </div>
                <div class="file-zone">
                    <input type="file" name="key_file" accept=".key" onchange="showName(this,'key')">
                    <div class="file-zone-label">Subir nueva .key</div>
                    <div class="file-zone-ext">Solo si renovaste la e.firma</div>
                    <div class="file-zone-name" id="name-key"></div>
                </div>
                @error('key_file') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;gap:0.75rem;margin-top:1.75rem;">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.firmantes.show', $firmante) }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
    function showName(input, suffix) {
        const el = document.getElementById('name-' + suffix);
        if (input.files.length > 0) {
            el.textContent = '✓ ' + input.files[0].name;
            el.style.display = 'block';
        }
    }
    </script>

</x-app-layout>
