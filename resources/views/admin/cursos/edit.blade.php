<x-app-layout>
    <x-slot name="header">
        <h2>Editar Curso</h2>
    </x-slot>

    <style>
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#94A3B8; margin-bottom:1.5rem; }
        .breadcrumb a { color:#64748b; text-decoration:none; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb-sep { color:#CBD5E1; }

        .page-grid { display:grid; grid-template-columns:1fr 340px; gap:1.25rem; align-items:start; }
        @media(max-width:900px){ .page-grid { grid-template-columns:1fr; } }

        .form-card { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); padding:1.75rem; }
        .form-group { margin-bottom:1.2rem; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
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

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.65rem 1.4rem; border-radius:9px; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:opacity 0.15s; text-decoration:none;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:0.65rem 1.3rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.875rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-cancel:hover { background:#F7F9FC; color:#64748b; }

        /* ── Template panel ── */
        .template-panel { background:#fff; border-radius:14px; border:1px solid #E8EDF4; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; }
        .template-panel-header { padding:1.1rem 1.25rem; border-bottom:1px solid #F0F4FA; display:flex; align-items:center; justify-content:space-between; }
        .template-panel-title { font-size:0.85rem; font-weight:700; color:#1E293B; }
        .template-panel-body { padding:1.25rem; }

        .template-thumb-wrap { position:relative; border-radius:10px; overflow:hidden; background: var(--brand-bg); border:1px solid var(--brand-alpha); margin-bottom:1rem; }
        .template-thumb { width:100%; height:160px; object-fit:cover; display:block; }
        .template-thumb-empty { width:100%; height:160px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; color: var(--brand); font-size:0.8rem; font-weight:500; }
        .template-thumb-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.2s; }
        .template-thumb-wrap:hover .template-thumb-overlay { opacity:1; }

        .btn-editor {
            display:flex; align-items:center; justify-content:center; gap:8px; width:100%;
            background: var(--brand-gradient); color:#fff; border:none;
            padding:0.7rem 1rem; border-radius:10px; font-size:0.875rem; font-weight:700;
            cursor:pointer; transition:opacity 0.15s; text-decoration:none;
            box-shadow:0 3px 12px var(--brand-alpha);
        }
        .btn-editor:hover { opacity:0.9; color:#fff; }

        .btn-create-template {
            display:flex; align-items:center; justify-content:center; gap:8px; width:100%;
            background: var(--brand-bg); color: var(--brand);
            border:2px dashed var(--brand); padding:0.85rem 1rem; border-radius:10px;
            font-size:0.875rem; font-weight:600; cursor:pointer; text-decoration:none;
            transition:background 0.15s;
        }
        .btn-create-template:hover { background: var(--brand-alpha); color: var(--brand); }

        .template-meta { font-size:0.75rem; color:#64748b; margin-bottom:1rem; }
        .template-meta span { display:flex; align-items:center; gap:5px; margin-bottom:4px; }

        .template-actions { display:flex; gap:0.5rem; margin-top:0.75rem; }
        .btn-small {
            flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
            padding:0.45rem 0.75rem; border-radius:8px; font-size:0.78rem; font-weight:600;
            text-decoration:none; border:1px solid #DDE3EF; background:#fff; color:#64748b;
            cursor:pointer; transition:background 0.1s;
        }
        .btn-small:hover { background:#F7F9FC; }
        .btn-small-danger { border-color:#FECACA; background:#FEF2F2; color:#DC2626; }
        .btn-small-danger:hover { background:#FEE2E2; }

        .dept-badge {
            display:inline-flex; align-items:center; gap:6px;
            background: var(--brand-bg); border:1px solid var(--brand-alpha);
            border-radius:8px; padding:0.4rem 0.85rem; font-size:0.82rem; font-weight:600; color: var(--brand);
            margin-bottom:1.5rem;
        }

        .alert-error { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error ul { margin:0.25rem 0 0 1rem; }
        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
    </style>

    <div class="breadcrumb">
        <a href="{{ route('admin.cursos.index') }}">Cursos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}">{{ $curso->nombre }}</a>
        <span class="breadcrumb-sep">/</span>
        <span>Editar</span>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
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

            <form action="{{ route('admin.cursos.update', $curso) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nombre del curso <span style="color:#DC2626">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $curso->nombre) }}"
                           class="form-input" required>
                    @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-textarea">{{ old('descripcion', $curso->descripcion) }}</textarea>
                    @error('descripcion') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-row">
                    <div>
                        <label class="form-label">Duración (horas)</label>
                        <input type="number" name="horas" value="{{ old('horas', $curso->horas) }}" min="1" class="form-input">
                        @error('horas') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Estado <span style="color:#DC2626">*</span></label>
                        <select name="estado" class="form-select">
                            @foreach(['borrador','activo','finalizado','cancelado'] as $e)
                                <option value="{{ $e }}" {{ old('estado', $curso->estado) === $e ? 'selected':'' }}>
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
                        <input type="date" name="fecha_inicio"
                               value="{{ old('fecha_inicio', $curso->fecha_inicio?->format('Y-m-d')) }}"
                               class="form-input">
                        @error('fecha_inicio') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Fecha de fin</label>
                        <input type="date" name="fecha_fin"
                               value="{{ old('fecha_fin', $curso->fecha_fin?->format('Y-m-d')) }}"
                               class="form-input">
                        @error('fecha_fin') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.cursos.index') }}" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>

        {{-- ── Panel plantilla Fabric ── --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            <div class="template-panel">
                <div class="template-panel-header">
                    <div class="template-panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;display:inline;margin-right:5px;vertical-align:-2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Plantilla del Diploma
                    </div>
                    @if($curso->template)
                        <span style="font-size:0.68rem; font-weight:700; background:#F0FDF4; color:#16A34A; padding:0.15rem 0.55rem; border-radius:20px;">Activa</span>
                    @else
                        <span style="font-size:0.68rem; font-weight:700; background:#F1F5F9; color:#64748B; padding:0.15rem 0.55rem; border-radius:20px;">Sin plantilla</span>
                    @endif
                </div>

                <div class="template-panel-body">
                    @if($curso->template)
                        @php $tpl = $curso->template; @endphp

                        {{-- Thumbnail del fondo --}}
                        <div class="template-thumb-wrap">
                            @if($tpl->background_image)
                                <img src="{{ Storage::url($tpl->background_image) }}" class="template-thumb" alt="{{ $tpl->nombre }}">
                            @else
                                <div class="template-thumb-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" style="width:36px;height:36px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                    Sin imagen de fondo
                                </div>
                            @endif
                            <div class="template-thumb-overlay">
                                <a href="{{ route('admin.templates.editor', $tpl) }}"
                                   style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.4); color:#fff; padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:600; text-decoration:none;">
                                    Abrir editor
                                </a>
                            </div>
                        </div>

                        {{-- Meta info --}}
                        <div class="template-meta">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-4.5 3h9M8.25 21h7.5"/>
                                </svg>
                                {{ $tpl->nombre }}
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>
                                </svg>
                                {{ $tpl->canvas_width }} × {{ $tpl->canvas_height }} px
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                {{ $tpl->elements->count() }} elemento(s)
                            </span>
                        </div>

                        {{-- Botón principal: Diseñar --}}
                        <a href="{{ route('admin.templates.editor', $tpl) }}" class="btn-editor">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:17px;height:17px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                            </svg>
                            Abrir Editor Drag & Drop
                        </a>

                        {{-- Acciones secundarias --}}
                        <div class="template-actions">
                            <a href="{{ route('admin.templates.edit', $tpl) }}" class="btn-small">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                                Configurar
                            </a>
                            <a href="{{ route('admin.templates.show', $tpl) }}" class="btn-small">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Vista previa
                            </a>
                            <form action="{{ route('admin.templates.destroy', $tpl) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar la plantilla «{{ $tpl->nombre }}»?')" style="display:contents;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-small-danger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>

                    @else
                        {{-- Sin plantilla: invitar a crear --}}
                        <div style="text-align:center; padding:1.5rem 0.5rem;">
                            <div style="width:56px;height:56px; border-radius:14px; background: var(--brand-bg); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="1.5" style="width:28px;height:28px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                                </svg>
                            </div>
                            <p style="font-size:0.875rem; font-weight:600; color:#1E293B; margin-bottom:0.3rem;">Sin plantilla de diploma</p>
                            <p style="font-size:0.78rem; color:#94A3B8; margin-bottom:1.25rem; line-height:1.5;">Crea una plantilla visual con el editor drag & drop para poder emitir diplomas en este curso.</p>

                            <a href="{{ route('admin.templates.create', ['curso_id' => $curso->id]) }}" class="btn-create-template">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Crear plantilla para este curso
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acceso rápido a alumnos --}}
            <a href="{{ route('admin.cursos.alumnos.index', $curso) }}"
               style="display:flex; align-items:center; gap:10px; background:#fff; border:1px solid #E8EDF4; border-radius:12px; padding:1rem 1.1rem; text-decoration:none; transition:box-shadow 0.15s;"
               onmouseover="this.style.boxShadow='0 4px 14px var(--brand-alpha)'" onmouseout="this.style.boxShadow='none'">
                <div style="width:38px;height:38px; border-radius:10px; background: var(--brand-bg); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="1.8" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:0.83rem; font-weight:700; color:#1E293B;">Gestionar alumnos</div>
                    <div style="font-size:0.75rem; color:#94A3B8;">Ver e inscribir alumnos al curso</div>
                </div>
                <svg viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="2" style="width:16px;height:16px;margin-left:auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>

        </div>
    </div>

</x-app-layout>
