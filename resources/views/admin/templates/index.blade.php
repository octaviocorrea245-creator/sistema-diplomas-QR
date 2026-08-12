<x-app-layout>
    <x-slot name="header">
        <h2>Plantillas de Diploma</h2>
    </x-slot>

    <style>
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            background: var(--brand); color:#fff; border:none;
            padding:0.55rem 1.1rem; border-radius:9px; font-size:0.83rem; font-weight:600;
            text-decoration:none; cursor:pointer; transition:opacity 0.15s;
        }
        .btn-primary:hover { opacity:0.87; color:#fff; }

        .template-grid {
            display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.25rem;
        }
        .template-card {
            background:#fff; border-radius:14px; border:1px solid #E8EDF4;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;
            transition:box-shadow 0.15s, transform 0.15s;
        }
        .template-card:hover { box-shadow:0 6px 20px var(--brand-alpha); transform:translateY(-2px); }

        .template-thumb {
            width:100%; height:140px; object-fit:cover; display:block; background:#F7F9FC;
        }
        .template-thumb-empty {
            width:100%; height:140px; display:flex; align-items:center; justify-content:center;
            background: var(--brand-bg); color: var(--brand); font-size:0.8rem; font-weight:500;
        }
        .template-body { padding:1rem 1.1rem; }
        .template-name { font-size:0.95rem; font-weight:600; color:#1E293B; margin-bottom:0.2rem; }
        .template-meta { font-size:0.78rem; color:#64748b; }
        .template-actions {
            display:flex; gap:0; border-top:1px solid #F0F4FA; padding:0;
        }
        .template-actions a, .template-actions button {
            flex:1; padding:0.6rem; text-align:center; font-size:0.78rem; font-weight:500;
            text-decoration:none; background:none; border:none; cursor:pointer;
            transition:background 0.1s; border-right:1px solid #F0F4FA;
        }
        .template-actions a:last-child, .template-actions button:last-child { border-right:none; }
        .template-actions a:hover, .template-actions button:hover { background:#F7F9FC; }
        .link-design  { color: var(--brand); }
        .link-view    { color:#64748b; }
        .link-edit    { color:#D97706; }
        .link-delete  { color:#DC2626; }

        .alert-success {
            background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D;
            border-radius:10px; padding:0.75rem 1.1rem; font-size:0.875rem; margin-bottom:1.25rem;
        }
        .empty-state { text-align:center; padding:3.5rem 1rem; color:#94A3B8; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <p style="font-size:0.82rem; color:#64748b; margin:0;">
            Diseña plantillas visuales para los diplomas de tu carrera
        </p>
        <a href="{{ route('admin.templates.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:15px;height:15px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Nueva Plantilla
        </a>
    </div>

    @if($templates->isEmpty())
        <div class="template-card" style="padding:0;">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand-light)" stroke-width="1.3" style="width:48px;height:48px;display:block;margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <p style="font-size:0.95rem; margin-bottom:0.25rem;">No hay plantillas aún</p>
                <p style="font-size:0.82rem;">Crea la primera plantilla visual para tus diplomas.</p>
            </div>
        </div>
    @else
        <div class="template-grid">
            @foreach($templates as $template)
                <div class="template-card">
                    @if($template->background_image)
                        <img src="{{ asset($template->background_image) }}" class="template-thumb" alt="{{ $template->nombre }}">
                    @else
                        <div class="template-thumb-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:32px;height:32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="template-body">
                        <div class="template-name">{{ $template->nombre }}</div>
                        <div class="template-meta">
                            {{ $template->curso->nombre ?? 'Sin curso' }}
                            &nbsp;·&nbsp;
                            {{ $template->canvas_width }} × {{ $template->canvas_height }} px
                        </div>
                    </div>

                    <div class="template-actions">
                        <a href="{{ route('admin.templates.editor', $template) }}" class="link-design">Diseñar</a>
                        <a href="{{ route('admin.templates.show', $template) }}" class="link-view">Ver</a>
                        <a href="{{ route('admin.templates.edit', $template) }}" class="link-edit">Editar</a>
                        <form action="{{ route('admin.templates.destroy', $template) }}" method="POST"
                              onsubmit="return confirmAction(event, '¿Eliminar «{{ $template->nombre }}»?')" style="display:contents;">
                            @csrf @method('DELETE')
                            <button type="submit" class="link-delete">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
