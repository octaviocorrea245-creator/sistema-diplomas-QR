<x-app-layout>
    <x-slot name="header">
        <h2>Generar diplomas masivamente</h2>
    </x-slot>

    <style>
        .page-grid { display:grid; grid-template-columns:1fr 300px; gap:1.25rem; align-items:start; }
        @media(max-width:860px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .section-label {
            font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase;
            margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA;
        }
        .section-label:first-child { margin-top:0; }

        .form-group { margin-bottom:1.1rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-input, .form-select {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; color:#1E293B;
            outline:none; transition:border-color 0.15s; background:#fff;
        }
        .form-input:focus, .form-select:focus { border-color:var(--brand); box-shadow:0 0 0 3px var(--brand-alpha); }
        .form-hint { font-size:0.75rem; color:#94A3B8; margin-top:4px; }
        .field-disabled {
            width:100%; border:1px solid #E8EDF4; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; color:#64748b;
            background:#F8FAFC;
        }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background:var(--brand); color:#fff; border:none;
            padding:0.65rem 1.5rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.65rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        /* Toggle switch */
        .toggle-row { display:flex; align-items:center; gap:10px; cursor:pointer; user-select:none; }
        .toggle-track {
            width:40px; height:22px; border-radius:11px; border:none; cursor:pointer;
            transition:background 0.2s; background:#CBD5E1; flex-shrink:0; position:relative;
        }
        .toggle-track[data-on="true"]  { background:var(--brand); }
        .toggle-thumb {
            position:absolute; top:3px; left:3px; width:16px; height:16px;
            border-radius:50%; background:#fff; transition:transform 0.2s;
            box-shadow:0 1px 3px rgba(0,0,0,0.2);
        }
        .toggle-track[data-on="true"] .toggle-thumb { transform:translateX(18px); }
        .toggle-label { font-size:0.875rem; font-weight:600; color:#1E293B; }
        .toggle-sub   { font-size:0.78rem; color:#64748b; }

        /* Firma box */
        .firma-box {
            background:var(--brand-bg); border:1px solid var(--brand-alpha);
            border-radius:12px; padding:1.25rem 1.5rem; margin-top:1rem;
        }
        .firma-box .section-label { margin-top:0; }

        /* Alert */
        .alert-error {
            background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem;
        }
        .alert-info {
            background:#EFF6FF; border:1px solid #BFDBFE; color:#1D4ED8;
            border-radius:10px; padding:0.85rem 1.1rem; font-size:0.82rem;
        }
        .notice {
            background:#FFFBEB; border:1px solid #FDE68A; color:#92400E;
            border-radius:10px; padding:0.85rem 1.1rem; font-size:0.82rem; margin-top:1rem;
        }

        /* Info sidebar */
        .info-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .info-card-header { background:var(--brand-gradient); padding:1.1rem 1.25rem; }
        .info-card-body { padding:1.25rem; }
        .info-row { margin-bottom:0.85rem; padding-bottom:0.85rem; border-bottom:1px solid #F0F4FA; font-size:0.82rem; }
        .info-row:last-child { margin-bottom:0; padding-bottom:0; border-bottom:none; }
        .info-row strong { display:block; color:#1E293B; font-weight:600; margin-bottom:3px; }
        .info-row span { color:#64748b; }

        [x-cloak] { display:none !important; }
    </style>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="page-grid" x-data="{
        firmar: {{ old('firmante_id') ? 'true' : 'false' }},
        confirming: false,
        confirmed: false
    }">

        <div class="form-card">
            <form method="POST" action="{{ route('admin.diplomas.mass.store') }}" id="mass-form"
                  @submit="if(!confirmed){ $event.preventDefault(); confirming = true } else { confirmed=false }">
                @csrf

                {{-- ── Curso ── --}}
                <div class="section-label">Curso y plantilla</div>

                <div class="form-group">
                    <label class="form-label">Curso</label>
                    @if($cursoPreseleccionado)
                        <input type="hidden" name="curso_id" value="{{ $cursoPreseleccionado->id }}">
                        <div class="field-disabled">{{ $cursoPreseleccionado->nombre }}</div>
                    @else
                        <select name="curso_id" required class="form-select">
                            <option value="">Seleccionar curso…</option>
                            @foreach($cursos as $c)
                                <option value="{{ $c->id }}" @selected(old('curso_id') == $c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Solo cursos activos o finalizados.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Plantilla de diploma</label>
                    @if($templatePreseleccionado)
                        <input type="hidden" name="template_id" value="{{ $templatePreseleccionado->id }}">
                        <div class="field-disabled">{{ $templatePreseleccionado->nombre }}</div>
                    @else
                        <select name="template_id" required class="form-select">
                            <option value="">Seleccionar plantilla…</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl->id }}" @selected(old('template_id') == $tpl->id)>
                                    {{ $tpl->nombre }} — {{ $tpl->curso->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Debe tener elementos diseñados en el editor.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha de emisión</label>
                    <input type="date" name="fecha_emision" required class="form-input"
                           value="{{ old('fecha_emision', date('Y-m-d')) }}">
                </div>

                {{-- ── Firma digital (opcional) ── --}}
                <div class="section-label" style="margin-top:1.75rem;">Firma electrónica</div>

                <div class="toggle-row" @click="firmar = !firmar" style="margin-bottom:0.5rem;">
                    <button type="button" class="toggle-track" :data-on="firmar.toString()">
                        <div class="toggle-thumb"></div>
                    </button>
                    <div>
                        <div class="toggle-label">Firmar al generar</div>
                        <div class="toggle-sub">Aplica firma digital e.firma SAT a cada diploma</div>
                    </div>
                </div>

                @if($firmantes->isEmpty())
                    <div class="alert-info" style="margin-top:0.75rem;">
                        No hay firmantes configurados en tu departamento.
                        <a href="{{ route('admin.firmantes.create') }}" style="color:var(--brand);font-weight:600;">Agregar firmante</a>
                    </div>
                @else
                    <div x-show="firmar" x-cloak x-transition class="firma-box">
                        <div class="section-label">Datos de firma</div>

                        <div class="form-group">
                            <label class="form-label">Firmante</label>
                            <select name="firmante_id" class="form-select" :required="firmar">
                                <option value="">Seleccionar firmante…</option>
                                @foreach($firmantes as $f)
                                    <option value="{{ $f->id }}" @selected(old('firmante_id') == $f->id)>
                                        {{ $f->nombre }} — {{ $f->cargo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Contraseña de llave privada (.key)</label>
                            <input type="password" name="password_firma" class="form-input"
                                   placeholder="Contraseña de tu e.firma"
                                   :required="firmar">
                            <div class="form-hint">Esta contraseña nunca se almacena.</div>
                        </div>
                    </div>

                    <div x-show="!firmar" class="notice" x-cloak x-transition>
                        Los diplomas se generarán <strong>sin firma digital</strong>. Puedes firmarlos individualmente después desde cada diploma.
                    </div>
                @endif

                {{-- Confirm modal overlay --}}
                <div x-show="confirming" x-cloak x-transition
                     style="position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:999;display:flex;align-items:center;justify-content:center;">
                    <div style="background:#fff;border-radius:16px;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);"
                         @click.stop>
                        <div style="font-size:1.05rem;font-weight:700;color:#1E293B;margin-bottom:0.5rem;">¿Confirmar generación?</div>
                        <p style="font-size:0.875rem;color:#64748b;margin-bottom:1.25rem;">
                            Se generarán diplomas para <strong>todos los alumnos inscritos</strong> del curso.<br>
                            Los que ya tengan diploma serán omitidos.<br>
                            <span x-show="firmar" style="color:var(--brand);font-weight:600;">Se aplicará firma electrónica a cada PDF.</span>
                        </p>
                        <div style="display:flex;gap:0.75rem;">
                            <button type="button" @click="confirming=false; confirmed=true; document.getElementById('mass-form').requestSubmit()"
                                    class="btn-primary">
                                Sí, generar
                            </button>
                            <button type="button" @click="confirming=false" class="btn-cancel">Cancelar</button>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.75rem;align-items:center;">
                    <button type="submit" class="btn-primary" id="submit-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Generar diplomas
                    </button>
                    <a href="{{ route('admin.diplomas.index') }}" class="btn-cancel">Cancelar</a>
                </div>

            </form>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.6);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:4px;">Información</div>
                    <div style="font-size:1rem;font-weight:700;color:#fff;">¿Cómo funciona?</div>
                </div>
                <div class="info-card-body">
                    <div class="info-row">
                        <strong>Generación masiva</strong>
                        <span>Se genera un PDF por cada alumno inscrito en el curso. Los que ya tenían diploma se omiten.</span>
                    </div>
                    <div class="info-row">
                        <strong>Firma electrónica</strong>
                        <span>Activa el toggle para aplicar tu e.firma SAT (PKCS#7). La contraseña no se guarda.</span>
                    </div>
                    <div class="info-row">
                        <strong>QR de verificación</strong>
                        <span>Cada diploma incluye un código QR único para verificación en línea.</span>
                    </div>
                    <div class="info-row">
                        <strong>Descarga</strong>
                        <span>Una vez generados, descarga todos los PDFs como ZIP desde la pantalla del curso.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
