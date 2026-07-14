<x-app-layout>
    <x-slot name="header">
        <h2>Firmar diploma</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; } .breadcrumb a:hover { color:var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .sign-grid { display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start; }
        @media(max-width:860px) { .sign-grid { grid-template-columns:1fr; } }

        .card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding:1.5rem; }
        .card-title { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:1rem; padding-bottom:0.6rem; border-bottom:1px solid #F0F4FA; }

        .diploma-info { }
        .diploma-info-row { display:flex; gap:8px; margin-bottom:0.75rem; font-size:0.875rem; }
        .diploma-info-key { color:#64748b; min-width:120px; flex-shrink:0; }
        .diploma-info-val { color:#1E293B; font-weight:500; }

        .firmante-badge {
            background:#F8FAFF; border:1px solid #BFDBFE; border-radius:10px;
            padding:1rem; display:flex; align-items:center; gap:12px; margin-bottom:1.25rem;
        }
        .firmante-avatar {
            width:44px;height:44px;border-radius:50%;background:var(--brand-bg);
            display:flex;align-items:center;justify-content:center;
            font-size:1rem;font-weight:700;color:var(--brand);flex-shrink:0;
        }

        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-hint  { font-size:0.75rem; color:#94A3B8; margin-top:0.3rem; }
        .password-wrap { position:relative; }
        .password-wrap input {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.65rem 2.75rem 0.65rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s; box-sizing:border-box; letter-spacing:0.1em;
        }
        .password-wrap input:focus { border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .toggle-pw {
            position:absolute; right:0.75rem; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer; color:#94A3B8; padding:0;
        }
        .toggle-pw:hover { color:#475569; }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        .btn-sign {
            width:100%; display:flex; align-items:center; justify-content:center; gap:8px;
            background:var(--brand); color:#fff; border:none;
            padding:0.75rem; border-radius:10px; font-size:0.9rem; font-weight:700;
            cursor:pointer; transition:opacity 0.15s; margin-top:1.25rem;
        }
        .btn-sign:hover { opacity:0.87; }
        .btn-sign:disabled { opacity:0.5; cursor:not-allowed; }
        .btn-cancel {
            width:100%; display:flex; align-items:center; justify-content:center;
            padding:0.65rem; border-radius:10px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
            margin-top:0.5rem;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        .warning-box {
            background:#FFFBEB; border:1px solid #FDE68A; border-radius:10px;
            padding:0.85rem 1rem; font-size:0.8rem; color:#92400E;
            display:flex; align-items:flex-start; gap:8px; margin-top:1rem;
        }

        .cert-detail { font-size:0.75rem; color:#64748b; margin-top:0.35rem; }
        .cert-detail span { font-family:monospace; color:#1E293B; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.firmantes.index') }}">Firmantes</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.firmantes.show', $firmante) }}">{{ $firmante->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Firmar diploma</span>
    </div>

    <div class="sign-grid">

        {{-- Info del diploma --}}
        <div class="card">
            <div class="card-body">
                <p class="card-title">Diploma a firmar</p>

                <div class="diploma-info">
                    <div class="diploma-info-row">
                        <span class="diploma-info-key">Alumno</span>
                        <span class="diploma-info-val">{{ $diploma->alumno->full_name ?? '—' }}</span>
                    </div>
                    <div class="diploma-info-row">
                        <span class="diploma-info-key">Curso</span>
                        <span class="diploma-info-val">{{ $diploma->curso->nombre ?? '—' }}</span>
                    </div>
                    <div class="diploma-info-row">
                        <span class="diploma-info-key">Folio</span>
                        <span class="diploma-info-val" style="font-family:monospace;font-size:0.875rem;">{{ $diploma->folio }}</span>
                    </div>
                    <div class="diploma-info-row">
                        <span class="diploma-info-key">Fecha emisión</span>
                        <span class="diploma-info-val">{{ $diploma->fecha_emision?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="diploma-info-row">
                        <span class="diploma-info-key">Estado firma</span>
                        <span>
                            @if($diploma->estaFirmado())
                                <span style="background:#F0FDF4;color:#16A34A;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.72rem;font-weight:600;">Ya firmado</span>
                            @else
                                <span style="background:#FFF7ED;color:#EA580C;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.72rem;font-weight:600;">Sin firma</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div style="margin-top:1.5rem;padding:1rem;background:#F7F9FC;border-radius:10px;font-size:0.82rem;color:#475569;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;color:#64748b;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    Al firmar, el PDF del diploma se regenera con la firma criptográfica PKCS#7 incrustada.
                    La operación sobreescribe el PDF actual.
                </div>
            </div>
        </div>

        {{-- Panel de firma --}}
        <div>
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Aplicar firma digital</p>

                    {{-- Firmante --}}
                    <div class="firmante-badge">
                        <div class="firmante-avatar">{{ strtoupper(substr($firmante->nombre,0,1)) }}</div>
                        <div>
                            <div style="font-weight:600;color:#1E293B;font-size:0.9rem;">{{ $firmante->nombre }}</div>
                            <div style="font-size:0.78rem;color:#64748b;">{{ $firmante->cargo }}</div>
                            @if($firmante->rfc)
                                <div class="cert-detail">RFC <span>{{ $firmante->rfc }}</span></div>
                            @endif
                            @if($firmante->cert_expira_en)
                                <div class="cert-detail">
                                    Cert. vence <span>{{ $firmante->cert_expira_en->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.firmantes.firmar', [$firmante, $diploma]) }}"
                          id="sign-form" onsubmit="return handleSubmit(this)">
                        @csrf

                        <label class="form-label">
                            Contraseña de e.firma <span style="color:#DC2626;">*</span>
                        </label>
                        <div class="password-wrap">
                            <input type="password" name="password" id="pw-input"
                                   placeholder="Contraseña del archivo .key"
                                   autocomplete="current-password" required>
                            <button type="button" class="toggle-pw" onclick="togglePw()" title="Mostrar/ocultar">
                                <svg id="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                        <p class="form-hint">La contraseña no se almacena en el servidor.</p>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror

                        <button type="submit" class="btn-sign" id="sign-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            Firmar diploma
                        </button>
                        <a href="{{ route('admin.firmantes.show', $firmante) }}" class="btn-cancel">Cancelar</a>
                    </form>

                    <div class="warning-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;flex-shrink:0;margin-top:1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                        Esta acción regenera el PDF y no se puede deshacer. Asegúrate de usar la contraseña correcta.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
    function togglePw() {
        const inp = document.getElementById('pw-input');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }
    function handleSubmit(form) {
        const btn = document.getElementById('sign-btn');
        btn.disabled = true;
        btn.textContent = 'Firmando…';
        return true;
    }
    </script>

</x-app-layout>
