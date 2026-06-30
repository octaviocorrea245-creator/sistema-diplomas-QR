<x-app-layout>
    <x-slot name="header">
        <h2>Nuevo Curso</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:1fr 320px; gap:1.25rem; align-items:start; }
        @media(max-width:900px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .form-group { margin-bottom:1.2rem; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .form-hint  { font-size:0.73rem; color:#94A3B8; margin-top:0.3rem; }
        .form-input, .form-select, .form-textarea {
            width:100%; border:1px solid #DDE3EF; border-radius:9px;
            padding:0.6rem 0.9rem; font-size:0.875rem; outline:none;
            transition:border 0.15s, box-shadow 0.15s; background:#fff; box-sizing:border-box;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--brand); box-shadow:0 0 0 3px var(--brand-alpha);
        }
        .form-textarea { resize:vertical; min-height:90px; }
        .form-error { font-size:0.75rem; color:#DC2626; margin-top:0.3rem; }

        .section-label { font-size:0.72rem; font-weight:700; color:#4A6585; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.9rem; padding-bottom:0.5rem; border-bottom:1px solid #F0F4FA; }

        .estado-option { display:flex; flex-direction:column; gap:2px; }
        .estado-option select option { padding:4px; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.65rem 1.4rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; }
        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:0.65rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        .info-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
        }
        .info-card-header { background: var(--brand-gradient); padding:1.1rem 1.25rem; }
        .info-card-body { padding:1.25rem; }
        .info-step { display:flex; align-items:flex-start; gap:0.75rem; margin-bottom:1rem; }
        .info-step:last-child { margin-bottom:0; }
        .step-num {
            width:24px; height:24px; border-radius:50%; background: var(--brand-bg);
            display:flex; align-items:center; justify-content:center;
            font-size:0.72rem; font-weight:800; color: var(--brand); flex-shrink:0;
        }
        .step-text { font-size:0.82rem; color:#334155; line-height:1.4; }
        .step-text strong { color:#1E293B; display:block; margin-bottom:2px; }

        .alert-error { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error ul { margin:0.25rem 0 0 1rem; }

        .dept-badge {
            display:inline-flex; align-items:center; gap:6px;
            background: var(--brand-bg); border:1px solid var(--brand-alpha);
            border-radius:8px; padding:0.4rem 0.85rem; font-size:0.82rem; font-weight:600; color: var(--brand);
            margin-bottom:1.5rem;
        }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <span>Nuevo</span>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <strong>Corrige los siguientes errores:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="page-grid">

        {{-- ── Formulario ── --}}
        <div class="form-card">
            <div class="dept-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008z"/>
                </svg>
                {{ auth()->user()->department->name ?? 'Sin departamento' }}
            </div>

            <div class="section-label">Información del curso</div>

            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nombre del curso <span style="color:#DC2626">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           class="form-input" placeholder="Ej: Introducción a Python" required>
                    @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-textarea"
                              placeholder="Describe brevemente el contenido del curso…">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-row">
                    <div>
                        <label class="form-label">Duración (horas)</label>
                        <input type="number" name="horas" value="{{ old('horas') }}" min="1"
                               class="form-input" placeholder="Ej: 40">
                        @error('horas') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Estado <span style="color:#DC2626">*</span></label>
                        <select name="estado" class="form-select">
                            @foreach(['borrador','activo','finalizado','cancelado'] as $e)
                                <option value="{{ $e }}" {{ old('estado','borrador') === $e ? 'selected':'' }}>
                                    {{ ucfirst($e) }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-input">
                        @error('fecha_inicio') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Fecha de fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-input">
                        @error('fecha_fin') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Guardar curso
                    </button>
                    <a href="{{ route('admin.cursos.index') }}" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>

        {{-- ── Panel lateral: pasos ── --}}
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <div style="font-size:0.72rem; color:rgba(255,255,255,0.6); font-weight:600; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:4px;">Flujo de trabajo</div>
                    <div style="font-size:1rem; font-weight:700; color:#fff;">Cómo funciona</div>
                </div>
                <div class="info-card-body">
                    <div class="info-step">
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <strong>Crea el curso</strong>
                            Define nombre, fechas y duración del curso.
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <strong>Inscribe alumnos</strong>
                            Agrega los beneficiarios que recibirán diploma.
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <strong>Diseña la plantilla</strong>
                            Usa el editor drag & drop para crear el diseño del diploma con variables dinámicas.
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-num">4</div>
                        <div class="step-text">
                            <strong>Emite diplomas</strong>
                            Genera diplomas individuales o de forma masiva con QR verificable.
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:1rem; background: var(--brand-bg); border:1px solid var(--brand-alpha); border-radius:12px; padding:1rem 1.1rem;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:0.4rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <span style="font-size:0.78rem; font-weight:700; color: var(--brand);">Después de guardar</span>
                </div>
                <p style="font-size:0.78rem; color:#475569; margin:0;">Podrás diseñar la plantilla del diploma con el editor visual desde la pantalla de edición del curso.</p>
            </div>
        </div>

    </div>

</x-app-layout>
