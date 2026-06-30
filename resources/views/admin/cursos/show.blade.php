<x-app-layout>
    <x-slot name="header">
        <h2>{{ $cursos->nombre }}</h2>
    </x-slot>

    <style>
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }
        .btn-ghost {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.55rem 1rem; border-radius:9px; border:1px solid #DDE3EF;
            font-size:0.83rem; color:#64748b; text-decoration:none; transition:background 0.1s;
        }
        .btn-ghost:hover { background:#F7F9FC; color:#64748b; }
        .btn-danger {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.55rem 1rem; border-radius:9px; border:1px solid #FECACA;
            font-size:0.83rem; color:#DC2626; text-decoration:none; background:#fff;
            transition:background 0.1s; cursor:pointer;
        }
        .btn-danger:hover { background:#FEF2F2; }
        .badge { display:inline-flex; align-items:center; padding:0.18rem 0.6rem; border-radius:20px; font-size:0.7rem; font-weight:600; white-space:nowrap; }
        .badge-green    { background:#F0FDF4; color:#16A34A; }
        .badge-gray     { background:#F1F5F9; color:#64748B; }
        .badge-blue     { background:#EFF6FF; color:#2563EB; }
        .badge-red      { background:#FEF2F2; color:#DC2626; }
        .badge-brand    { background: var(--brand-bg); color: var(--brand); border:1px solid var(--brand-alpha); }

        .section-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden; margin-bottom:1.25rem;
        }
        .section-header {
            display:flex; align-items:center; gap:0.65rem;
            padding:0.85rem 1.1rem; border-bottom:1px solid #E8EDF4;
            font-size:0.82rem; font-weight:700; color:#1E293B;
        }

        .stat-card {
            background:#fff; border-radius:12px; border:1px solid #E8EDF4; padding:1rem;
            text-decoration:none; display:block; transition:box-shadow 0.15s, transform 0.15s;
            position:relative; overflow:hidden;
        }
        .stat-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.06); transform:translateY(-1px); }
        .stat-card .stat-number { font-size:1.6rem; font-weight:800; line-height:1.2; }
        .stat-card .stat-label { font-size:0.7rem; text-transform:uppercase; letter-spacing:0.06em; font-weight:600; margin-top:0.25rem; color:#64748B; }

        .preview-wrapper {
            position:relative; overflow:hidden; border-radius:8px; border:1px solid #E8EDF4;
            background:#f8fafc; cursor:pointer;
        }
        .preview-overlay {
            position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
            opacity:0; transition:opacity 0.2s; background:rgba(0,0,0,0.35);
            border-radius:8px;
        }
        .preview-wrapper:hover .preview-overlay { opacity:1; }
        .preview-overlay .btn-edit-overlay {
            background:#fff; color:#1E293B; border:none; padding:0.5rem 1.2rem;
            border-radius:8px; font-size:0.8rem; font-weight:600; cursor:pointer;
            text-decoration:none; box-shadow:0 2px 12px rgba(0,0,0,0.2);
        }
        .preview-overlay .btn-edit-overlay:hover { background:#f1f5f9; }

        .toast-container {
            position:fixed; top:1rem; right:1rem; z-index:9999;
            display:flex; flex-direction:column; gap:0.5rem;
        }
        .toast-item {
            padding:0.75rem 1.1rem; border-radius:10px; font-size:0.85rem; font-weight:500;
            box-shadow:0 4px 20px rgba(0,0,0,0.12); min-width:260px; max-width:380px;
            animation: toastSlideIn 0.25s ease; color:#fff;
        }
        .toast-success { background:#16A34A; }
        .toast-error   { background:#DC2626; }
        .toast-info    { background: var(--brand); }
        @keyframes toastSlideIn { from { transform:translateX(100%); opacity:0; } to { transform:translateX(0); opacity:1; } }
        @keyframes toastSlideOut { from { opacity:1; } to { opacity:0; transform:translateX(100%); } }

        .diploma-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
        .diploma-table thead th { background:#F7F9FC; border-bottom:1px solid #E8EDF4; padding:0.6rem 0.9rem; text-align:left; font-size:0.7rem; font-weight:600; color:#4A6585; letter-spacing:0.06em; text-transform:uppercase; }
        .diploma-table tbody tr { border-bottom:1px solid #F0F4FA; transition:background 0.1s; }
        .diploma-table tbody tr:last-child { border-bottom:none; }
        .diploma-table tbody tr:hover { background:#F7F9FC; }
        .diploma-table td { padding:0.6rem 0.9rem; color:#334155; }

        .alert-success { background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem; }

        .empty-state { text-align:center; padding:2.5rem 1rem; }
        .empty-state svg { display:block; margin:0 auto 0.75rem; }

        .action-link { font-size:0.8rem; font-weight:500; text-decoration:none; transition:opacity 0.1s; }
        .action-link:hover { opacity:0.7; }
        .link-view   { color: var(--brand); }
    </style>

    {{-- Toast Container --}}
    <div id="toastContainer" class="toast-container"></div>

    {{-- Flash toasts --}}
    @if(session('toast'))
        @php $t = session('toast'); @endphp
        <script>document.addEventListener('DOMContentLoaded',function(){showToast({{ json_encode($t['message']) }},{{ json_encode($t['type']) }});});</script>
    @endif
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded',function(){showToast({{ json_encode(session('success')) }},'success');});</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded',function(){showToast({{ json_encode(session('error')) }},'error');});</script>
    @endif

    {{-- ════════════════════════════════════════ --}}
    {{-- SECTION 1: General Info --}}
    {{-- ════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
            Información del Curso
        </div>
        <div style="padding:1.1rem;">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div style="flex:1; min-width:200px;">
                    <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                        <h3 style="font-size:1.1rem; font-weight:700; color:#1E293B; margin:0;">{{ $cursos->nombre }}</h3>
                        @php
                            $badgeClass = match($cursos->estado) {
                                'activo' => 'badge-green', 'finalizado' => 'badge-blue',
                                'cancelado' => 'badge-red', default => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($cursos->estado) }}</span>
                    </div>
                    @if($cursos->descripcion)
                        <p style="font-size:0.82rem; color:#64748B; margin-top:0.5rem; margin-bottom:0;">{{ $cursos->descripcion }}</p>
                    @endif
                </div>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    <a href="{{ route('admin.cursos.edit', $cursos) }}" class="btn-ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21h-9.5A2.25 2.25 0 014 18.75V14"/></svg>
                        Editar
                    </a>
                    <a href="{{ route('admin.cursos.index') }}" class="btn-ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                        Volver
                    </a>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px,1fr)); gap:0.75rem; margin-top:1rem; padding-top:1rem; border-top:1px solid #F0F4FA;">
                <div><span style="font-size:0.7rem; font-weight:700; color:#4A6585; text-transform:uppercase; letter-spacing:0.06em;">Departamento</span><p style="font-size:0.85rem; color:#1E293B; font-weight:500; margin-top:0.2rem;">{{ $cursos->departamento->name }}</p></div>
                <div><span style="font-size:0.7rem; font-weight:700; color:#4A6585; text-transform:uppercase; letter-spacing:0.06em;">Horas</span><p style="font-size:0.85rem; color:#1E293B; font-weight:500; margin-top:0.2rem;">{{ $cursos->horas ?? '—' }}</p></div>
                <div><span style="font-size:0.7rem; font-weight:700; color:#4A6585; text-transform:uppercase; letter-spacing:0.06em;">Inicio</span><p style="font-size:0.85rem; color:#1E293B; font-weight:500; margin-top:0.2rem;">{{ $cursos->fecha_inicio ? \Carbon\Carbon::parse($cursos->fecha_inicio)->format('d/m/Y') : '—' }}</p></div>
                <div><span style="font-size:0.7rem; font-weight:700; color:#4A6585; text-transform:uppercase; letter-spacing:0.06em;">Fin</span><p style="font-size:0.85rem; color:#1E293B; font-weight:500; margin-top:0.2rem;">{{ $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('d/m/Y') : '—' }}</p></div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════ --}}
    {{-- SECTION 2: Student Stats --}}
    {{-- ════════════════════════════════════════ --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px,1fr)); gap:0.75rem; margin-bottom:1.25rem;">
        <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos]) }}" class="stat-card">
            <div class="stat-number" style="color:#2563EB;">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Alumnos</div>
        </a>
        <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'inscrito']) }}" class="stat-card">
            <div class="stat-number" style="color:#2563EB;">{{ $stats['inscrito'] }}</div>
            <div class="stat-label">Inscritos</div>
        </a>
        <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'en_curso']) }}" class="stat-card">
            <div class="stat-number" style="color:#D97706;">{{ $stats['en_curso'] }}</div>
            <div class="stat-label">En Curso</div>
        </a>
        <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'completado']) }}" class="stat-card">
            <div class="stat-number" style="color:#16A34A;">{{ $stats['completado'] }}</div>
            <div class="stat-label">Completados</div>
        </a>
        <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'baja']) }}" class="stat-card">
            <div class="stat-number" style="color:#DC2626;">{{ $stats['baja'] }}</div>
            <div class="stat-label">Bajas</div>
        </a>
    </div>

    {{-- ════════════════════════════════════════ --}}
    {{-- SECTION 3: Template Card --}}
    {{-- ════════════════════════════════════════ --}}
    @php $template = $cursos->template; @endphp
    <div class="section-card">
        <div class="section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;color:var(--brand);"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            Plantilla de Diploma
            @if($template)
                <span class="badge badge-green" style="margin-left:auto;">Creada</span>
            @else
                <span class="badge badge-gray" style="margin-left:auto;">Sin plantilla</span>
            @endif
        </div>

        <div style="padding:1.1rem;">
            @if($template)
                <div style="display:flex; gap:1.25rem; flex-wrap:wrap;">
                    {{-- Preview --}}
                    <div class="preview-wrapper" style="width:220px; height:155px; flex-shrink:0;">
                        <div id="previewInner" style="transform-origin:top left; width:{{ $template->canvas_width }}px;">
                            @if($template->background_image)
                                <img src="{{ asset($template->background_image) }}" style="position:absolute;left:0;top:0;width:100%;height:100%;object-fit:cover;pointer-events:none;">
                            @else
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#CBD5E1;font-size:0.75rem;">Sin fondo</div>
                            @endif
                            @foreach($template->elements->take(5) as $el)
                                @php $cfg = $el->config_json ?? []; @endphp
                                @if($el->tipo === 'text' || $el->tipo === 'variable')
                                    <div style="position:absolute; left:{{ $el->x }}px; top:{{ $el->y }}px; font-size:{{ ($cfg['fontSize'] ?? 24) }}px; color:{{ $cfg['fill'] ?? '#000' }}; font-weight:{{ !empty($cfg['bold']) ? 'bold' : 'normal' }};">{{ $cfg['text'] ?? ($el->variable ?? 'Texto') }}</div>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('admin.templates.editor', $template) }}" class="preview-overlay">
                            <span class="btn-edit-overlay">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                Editar Plantilla
                            </span>
                        </a>
                    </div>

                    {{-- Info --}}
                    <div style="flex:1; min-width:200px;">
                        <h4 style="font-size:0.95rem; font-weight:700; color:#1E293B; margin:0 0 0.4rem;">{{ $template->nombre }}</h4>
                        <div style="display:flex; flex-wrap:wrap; gap:0.4rem 1rem; font-size:0.78rem; color:#64748B; margin-bottom:0.75rem;">
                            <span>{{ $template->canvas_width }} × {{ $template->canvas_height }} px</span>
                            <span>{{ $template->elements->count() }} elemento(s)</span>
                            <span>Creado {{ $template->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                            <a href="{{ route('admin.templates.editor', $template) }}" class="btn-primary" style="padding:0.45rem 0.9rem; font-size:0.78rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                Diseñar
                            </a>
                            <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="btn-ghost" style="padding:0.45rem 0.9rem; font-size:0.78rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Vista Previa
                            </a>
                            @if($template->background_image)
                            <form action="{{ route('admin.templates.remove-background', $template) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-ghost" style="padding:0.45rem 0.9rem; font-size:0.78rem; color:#DC2626; border-color:#FECACA;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Quitar Fondo
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" onsubmit="return confirm('¿Eliminar esta plantilla y todos sus elementos?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding:0.45rem 0.9rem; font-size:0.78rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:44px;height:44px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    <p style="font-size:0.9rem; color:#64748B; margin-bottom:0.75rem;">Este curso aún no tiene una plantilla de diploma.</p>
                    <form action="{{ route('admin.templates.create-for-course', $cursos) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Crear Plantilla
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- ════════════════════════════════════════ --}}
    {{-- SECTION 4: Export --}}
    {{-- ════════════════════════════════════════ --}}
    @if($template)
    <div class="section-card">
        <div class="section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;color:var(--brand);"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Exportación de Diplomas
        </div>
        <div style="padding:1.1rem;">
            @php
                $generatedCount = $diplomas->count();
                $elegibleCount = $eligibleAlumnos;
            @endphp
            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-size:0.78rem; color:#64748B;">Diplomas generados:</span>
                    <span style="font-size:1.2rem; font-weight:700; color:{{ $generatedCount > 0 ? '#16A34A' : '#64748B' }};">{{ $generatedCount }}</span>
                    <span style="font-size:0.78rem; color:#94A3B8;">/ {{ $elegibleCount }} elegibles</span>
                </div>
                @if($generatedCount > 0)
                    <div style="width:120px; height:6px; background:#E8EDF4; border-radius:3px; overflow:hidden;">
                        <div style="height:100%; background:var(--brand-gradient); border-radius:3px; width:{{ $elegibleCount > 0 ? min(100, ($generatedCount/$elegibleCount)*100) : 0 }}%;"></div>
                    </div>
                @endif
            </div>

            <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                <form action="{{ route('admin.diplomas.mass.store') }}" method="POST" style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                    <input type="hidden" name="template_id" value="{{ $template->id }}">
                    <input type="hidden" name="fecha_emision" value="{{ now()->format('Y-m-d') }}">
                    <button type="submit" class="btn-primary" onclick="return confirm('¿Generar diplomas para todos los alumnos elegibles?')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18h.375a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.375v-.621c0-.832-.672-1.5-1.5-1.5h-.187a48.415 48.415 0 01-4.126 0h-.187c-.828 0-1.5.668-1.5 1.5v.621m0 0V21h9v-2.625M12 3.75h4.5a1.5 1.5 0 011.5 1.5v1.5c0 .828-.672 1.5-1.5 1.5H12a1.5 1.5 0 01-1.5-1.5v-1.5c0-.828.672-1.5 1.5-1.5z"/></svg>
                        Generar Diplomas
                    </button>
                </form>

                @if($generatedCount > 0)
                    <a href="{{ route('admin.diplomas.mass.download-all', $cursos) }}" class="btn-primary" style="background:#059669;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Descargar ZIP
                    </a>

                    <form action="{{ route('admin.diplomas.mass.regenerate', $cursos) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar todos los diplomas, PDFs y QRs para regenerarlos desde cero?')">
                        @csrf
                        <button type="submit" class="btn-danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                            Regenerar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════ --}}
    {{-- SECTION 5: Diploma History --}}
    {{-- ════════════════════════════════════════ --}}
    @if($template && $diplomas->isNotEmpty())
    <div class="section-card">
        <div class="section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;color:var(--brand);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Historial de Diplomas
            <span class="badge badge-brand" style="margin-left:auto;">{{ $diplomas->count() }} diploma(s)</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="diploma-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Folio</th>
                        <th>Fecha Emisión</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diplomas as $diploma)
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#1E293B;">{{ $diploma->alumno?->full_name ?? '—' }}</div>
                        </td>
                        <td style="font-family:monospace; font-size:0.78rem; color:#64748B;">{{ $diploma->folio }}</td>
                        <td style="font-size:0.78rem; color:#64748B;">{{ $diploma->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <span class="badge badge-green">{{ ucfirst($diploma->estado) }}</span>
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.diplomas.mass.download', $diploma) }}" class="action-link link-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;display:inline;vertical-align:middle;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <script>
        function showToast(message, type) {
            var container = document.getElementById('toastContainer');
            var el = document.createElement('div');
            el.className = 'toast-item toast-' + (type || 'info');
            el.textContent = message;
            container.appendChild(el);
            setTimeout(function() {
                el.style.animation = 'toastSlideOut 0.25s ease forwards';
                setTimeout(function() { el.remove(); }, 250);
            }, 4000);
        }

        // Preview scaling
        (function() {
            var wrapper = document.querySelector('.preview-wrapper');
            var inner = document.getElementById('previewInner');
            if (!wrapper || !inner) return;
            var cw = {{ $template->canvas_width ?? 1920 }};
            var ch = {{ $template->canvas_height ?? 1358 }};
            function scalePreview() {
                var w = wrapper.clientWidth - 2;
                var h = wrapper.clientHeight - 2;
                var s = Math.min(w / cw, h / ch, 1);
                inner.style.transform = 'scale(' + s + ')';
                inner.style.width = cw + 'px';
                inner.style.height = ch + 'px';
                inner.style.position = 'relative';
            }
            scalePreview();
            window.addEventListener('resize', scalePreview);
        })();
    </script>
</x-app-layout>
