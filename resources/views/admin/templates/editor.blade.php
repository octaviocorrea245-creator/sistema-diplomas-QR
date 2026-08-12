<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
            <div style="display:flex; align-items:center; gap:8px;">
                <a href="{{ route('admin.templates.show', $template) }}"
                   style="display:flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:6px; border:1px solid #DDE3EF; background:#fff; color:#64748b; text-decoration:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </a>
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--brand)" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                <span style="font-weight:700; font-size:0.9rem; color:#1E293B;">{{ $template->nombre }}</span>
                <span style="font-size:0.7rem; background:#F1F5F9; color:#64748B; border:1px solid #E2E8F0; padding:2px 7px; border-radius:5px; font-family:monospace; font-weight:600;">{{ $template->canvas_width }}×{{ $template->canvas_height }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:5px;">
                <button onclick="undo()" id="undoBtn" disabled title="Ctrl+Z" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:28px;border:1px solid #DDE3EF;border-radius:6px;background:#fff;cursor:pointer;color:#64748b;" onmouseover="this.style.background='#F7F9FC'" onmouseout="this.style.background='#fff'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                </button>
                <button onclick="redo()" id="redoBtn" disabled title="Ctrl+Y" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:28px;border:1px solid #DDE3EF;border-radius:6px;background:#fff;cursor:pointer;color:#64748b;" onmouseover="this.style.background='#F7F9FC'" onmouseout="this.style.background='#fff'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3"/></svg>
                </button>
                <div style="width:1px;height:16px;background:#E2E8F0;margin:0 2px;"></div>
                <button onclick="previewCanvas()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border:1px solid #DDE3EF;border-radius:6px;background:#fff;cursor:pointer;font-size:0.78rem;color:#475569;" onmouseover="this.style.background='#F7F9FC'" onmouseout="this.style.background='#fff'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Previsualizar
                </button>
                <button onclick="saveCanvas()" id="saveBtn" style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border:none;border-radius:6px;background:var(--brand);color:#fff;cursor:pointer;font-size:0.82rem;font-weight:700;box-shadow:0 2px 8px var(--brand-alpha);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Guardar
                </button>
            </div>
        </div>
    </x-slot>

    {{-- ── Override layout padding for full-screen editor ── --}}
    <style>
        /* Fill viewport exactly — flex approach, no hardcoded pixel offsets */
        #app-main        { height: 100vh !important; min-height: unset !important; overflow: hidden !important; }
        #app-main > main { padding: 0 !important; flex: 1 !important; min-height: 0 !important;
                           display: flex !important; flex-direction: column !important; overflow: hidden !important; }

        /* ── Editor skeleton ── */
        *, *::before, *::after { box-sizing: border-box; }

        .wp-editor {
            flex: 1; min-height: 0;
            display: flex;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 13px;
            color: #3c434a;
        }

        /* ─── LEFT SIDEBAR ─── */
        .wp-sidebar {
            width: 244px;
            min-width: 244px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Tab bar — WP-style */
        .wp-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            background: #f6f7f7;
        }
        .wp-tab {
            flex: 1; padding: 10px 6px;
            border: none; background: none;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: #646970; cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: color .1s;
        }
        .wp-tab:hover { color: #1e1e1e; }
        .wp-tab.active { color: var(--brand); border-bottom-color: var(--brand); background: #fff; }

        .wp-panel { flex: 1; overflow-y: auto; }
        .wp-panel.hidden { display: none !important; }

        /* Section titles — Gutenberg style */
        .wp-section-title {
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #646970; padding: 14px 16px 6px;
        }
        .wp-section-divider { height: 1px; background: #e0e0e0; margin: 8px 0; }

        /* Element grid — WP block inserter */
        .el-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 4px;
            padding: 0 12px;
        }
        .el-card {
            display: flex; flex-direction: column;
            align-items: center; gap: 5px;
            padding: 10px 4px 8px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            cursor: grab;
            transition: all .12s;
            user-select: none;
            font-size: 10.5px; font-weight: 500; color: #3c434a;
            text-align: center;
        }
        .el-card:hover {
            border-color: var(--brand);
            background: var(--brand-bg);
            color: var(--brand);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px var(--brand-alpha);
        }
        .el-card:hover .el-icon { background: var(--brand-bg); color: var(--brand); }
        .el-card:active { cursor: grabbing; transform: scale(.96); }
        .el-card.dragging { opacity: .5; }
        .el-icon {
            width: 32px; height: 32px; border-radius: 5px;
            background: #f6f7f7; display: flex; align-items: center; justify-content: center;
            color: #646970; transition: all .12s;
        }

        /* Background upload */
        .bg-upload {
            display: flex; align-items: center; gap: 7px;
            padding: 8px 10px; margin: 0 12px;
            background: #f6f7f7; border: 1.5px dashed #b4b9be;
            border-radius: 4px; cursor: pointer;
            font-size: 12px; font-weight: 500; color: var(--brand);
            transition: all .12s; width: calc(100% - 24px);
        }
        .bg-upload:hover { border-color: var(--brand); background: var(--brand-bg); }
        .bg-upload input { display: none; }

        /* Variable chips */
        .var-chips { display: flex; flex-wrap: wrap; gap: 3px; padding: 0 12px; }
        .var-chip {
            display: inline-flex; align-items: center;
            padding: 3px 8px; background: #f6f7f7;
            border: 1px solid #e0e0e0; border-radius: 2px;
            font-size: 10.5px; cursor: pointer; transition: all .1s;
        }
        .var-chip:hover { border-color: var(--brand); background: var(--brand-bg); }
        .var-chip code { color: var(--brand); font-weight: 700; font-size: 10.5px; font-family: monospace; }

        /* Layer list */
        .layer-list { padding: 4px 8px; }
        .layer-item {
            display: flex; align-items: center; gap: 7px;
            padding: 6px 8px; margin-bottom: 2px;
            background: #fff; border: 1px solid #e0e0e0; border-radius: 3px;
            cursor: pointer; font-size: 11.5px; transition: all .1s;
        }
        .layer-item:hover { border-color: var(--brand); background: var(--brand-bg); }
        .layer-item.active { border-color: var(--brand); background: var(--brand-bg); }
        .layer-icon {
            width: 20px; height: 20px; border-radius: 3px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; background: var(--brand); color: #fff;
        }
        .layer-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #3c434a; font-weight: 500; }
        .layer-del { width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; color: #b4b9be; font-size: 11px; cursor: pointer; }
        .layer-del:hover { color: #d63638; }

        /* ─── CANVAS AREA ─── */
        .wp-canvas-area {
            flex: 1; display: flex; flex-direction: column;
            background: #dcdcdc; overflow: hidden;
        }

        .wp-canvas-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 5px 12px; background: #fff;
            border-bottom: 1px solid #e0e0e0; min-height: 36px;
        }
        .canvas-info { display: flex; align-items: center; gap: 6px; }
        .canvas-dot { width: 6px; height: 6px; border-radius: 50%; background: #b4b9be; flex-shrink: 0; }
        .canvas-sel { font-size: 11px; color: #646970; font-family: monospace; }

        .canvas-tools { display: flex; align-items: center; gap: 3px; }
        .c-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 26px; border: 1px solid #e0e0e0; border-radius: 3px;
            background: #fff; cursor: pointer; color: #555; font-size: 14px; font-weight: 300;
            transition: all .1s;
        }
        .c-btn:hover { background: #f6f7f7; border-color: #b4b9be; }
        .c-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }
        .c-zoom { font-size: 11px; font-family: monospace; min-width: 42px; text-align: center; color: #3c434a; font-weight: 700; }

        .wp-canvas-viewport {
            flex: 1; overflow: auto; display: flex;
            align-items: flex-start; justify-content: center;
            padding: 32px; position: relative;
        }
        #canvasWrapper {
            /* Sized dynamically by applyZoom() to the VISUAL dimensions so flex centering works */
            box-shadow: 0 4px 32px rgba(0,0,0,.2);
            background: #fff; position: relative;
            overflow: hidden; flex-shrink: 0;
        }
        /* Fabric's internal wrapper is CSS-scaled — canvas DOM stays at W×H */
        .canvas-container { transform-origin: top left; }
        #diplomaCanvas { display: block; }
        .canvas-grid #canvasWrapper {
            background-image: linear-gradient(rgba(0,0,0,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,.04) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* ─── RIGHT INSPECTOR PANEL ─── */
        .wp-inspector {
            width: 280px; min-width: 280px; background: #fff;
            border-left: 1px solid #e0e0e0; overflow-y: auto;
        }

        .wp-insp-placeholder {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; text-align: center; padding: 48px 20px;
        }

        /* Inspector block header */
        .insp-block-header {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px; border-bottom: 1px solid #e0e0e0;
            background: #f6f7f7;
        }
        .insp-block-icon {
            width: 34px; height: 34px; border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            background: var(--brand-bg); color: var(--brand);
            font-size: 14px; font-weight: 800; flex-shrink: 0;
        }

        /* Inspector tabs — WP "Block | Document" style */
        .insp-tabs {
            display: flex; border-bottom: 1px solid #e0e0e0;
        }
        .insp-tab {
            flex: 1; padding: 8px 4px; border: none; background: none;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: .04em; color: #646970; cursor: pointer;
            border-bottom: 2px solid transparent; transition: color .1s;
        }
        .insp-tab:hover { color: #1e1e1e; }
        .insp-tab.active { color: var(--brand); border-bottom-color: var(--brand); background: #fff; }

        .insp-content { display: none; }
        .insp-content.active { display: block; }

        /* Inspector sections — WP panel blocks */
        .insp-panel {
            border-bottom: 1px solid #e0e0e0;
        }
        .insp-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; cursor: default;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: .05em; color: #646970;
        }
        .insp-panel-body { padding: 4px 14px 14px; }

        /* Form controls — WP style */
        .wp-label {
            display: block; font-size: 11px; font-weight: 600;
            color: #646970; margin-bottom: 4px;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .wp-input, .wp-select, .wp-textarea {
            width: 100%; padding: 6px 8px; font-size: 12px;
            border: 1px solid #b4b9be; border-radius: 2px;
            background: #fff; color: #1e1e1e; outline: none;
            transition: border .12s, box-shadow .12s;
        }
        .wp-input:focus, .wp-select:focus, .wp-textarea:focus {
            border-color: var(--brand); box-shadow: 0 0 0 1px var(--brand);
        }
        .wp-textarea { resize: vertical; min-height: 70px; }

        .wp-row { display: flex; gap: 6px; }
        .wp-row > * { flex: 1; }

        /* Style buttons */
        .fmt-btn {
            width: 28px; height: 28px; display: flex; align-items: center;
            justify-content: center; border: 1px solid #b4b9be; border-radius: 2px;
            background: #fff; cursor: pointer; color: #3c434a; font-size: 12px;
            transition: all .1s;
        }
        .fmt-btn:hover { background: #f6f7f7; }
        .fmt-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }

        .align-grp { display: flex; border: 1px solid #b4b9be; border-radius: 2px; overflow: hidden; }
        .align-btn {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 5px; border: none; background: #fff; cursor: pointer;
            color: #646970; transition: background .1s;
        }
        .align-btn:not(:last-child) { border-right: 1px solid #e0e0e0; }
        .align-btn:hover { background: #f6f7f7; }
        .align-btn.active { background: var(--brand); color: #fff; }

        .color-swatch { height: 28px; border: 1px solid #b4b9be; border-radius: 2px; padding: 2px; cursor: pointer; }

        .wp-range { width: 100%; accent-color: var(--brand); }

        .wp-btn {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            padding: 6px 10px; border: 1px solid #b4b9be; border-radius: 2px;
            background: #fff; color: #3c434a; font-size: 11.5px; cursor: pointer; width: 100%;
            transition: background .1s;
        }
        .wp-btn:hover { background: #f6f7f7; }
        .wp-btn-danger {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 7px; border: 1px solid #f8d7da; border-radius: 2px;
            background: #fff5f5; color: #d63638; font-size: 11.5px; font-weight: 600; cursor: pointer;
            transition: background .1s;
        }
        .wp-btn-danger:hover { background: #fce7e7; }

        /* Layer order buttons */
        .order-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }

        /* ─── Context menu — WP style ─── */
        .wp-ctx {
            position: fixed; z-index: 9999; min-width: 165px;
            background: #fff; border: 1px solid #e0e0e0;
            border-radius: 2px; box-shadow: 0 6px 20px rgba(0,0,0,.13); padding: 4px 0;
        }
        .ctx-item {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 6px 12px; border: none; background: none;
            text-align: left; font-size: 12px; color: #3c434a;
            cursor: pointer; transition: background .08s;
        }
        .ctx-item:hover { background: var(--brand-bg); color: var(--brand); }
        .ctx-danger { color: #d63638; }
        .ctx-danger:hover { background: #fff5f5; color: #d63638; }
        .ctx-sep { height: 1px; background: #e0e0e0; margin: 3px 0; }

        /* ─── Toasts ─── */
        .toast-box { position: fixed; bottom: 18px; right: 18px; z-index: 99999; display: flex; flex-direction: column; gap: 7px; }
        .toast {
            padding: 9px 14px; border-radius: 3px; font-size: 12px; font-weight: 500;
            box-shadow: 0 4px 14px rgba(0,0,0,.15); max-width: 260px;
            animation: toastIn .18s ease;
        }
        .toast-success { background: #00a32a; color: #fff; }
        .toast-error   { background: #d63638; color: #fff; }
        .toast-info    { background: var(--brand); color: #fff; }
        @keyframes toastIn  { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes toastOut { from { opacity: 1; } to { opacity: 0; transform: translateY(8px); } }
        @keyframes spin { to { transform: rotate(360deg); } }

        .hidden { display: none !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d0d5db; border-radius: 3px; }
    </style>

    <div class="wp-editor" id="app">

        {{-- ════ LEFT SIDEBAR ════ --}}
        <div class="wp-sidebar">
            <div class="wp-tabs">
                <button class="wp-tab active" data-panel="elements" onclick="switchPanel('elements')">Elementos</button>
                <button class="wp-tab" data-panel="layers" onclick="switchPanel('layers')">Capas</button>
            </div>

            {{-- ── Elements panel ── --}}
            <div class="wp-panel" id="panel-elements">

                <div class="wp-section-title">Agregar elemento</div>
                <div class="el-grid">
                    <div draggable="true" ondragstart="onDragStart(event, 'text')" onclick="addText()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-4.5 3h9M8.25 21h7.5"/></svg>
                        </div>
                        <span>Texto</span>
                    </div>

                    <div draggable="true" ondragstart="onDragStart(event, 'qr')" onclick="addQr()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM3.75 15.375c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM15 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/></svg>
                        </div>
                        <span>QR</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'rect')" onclick="addRect()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><rect x="3.75" y="3.75" width="16.5" height="16.5" rx="1.5"/></svg>
                        </div>
                        <span>Figura</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'line')" onclick="addLine()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5"/></svg>
                        </div>
                        <span>Línea</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'image')" onclick="addImage()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                        </div>
                        <span>Imagen</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'firma')" onclick="addFirma()" class="el-card">
                        <div class="el-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                        </div>
                        <span>Firma</span>
                    </div>
                </div>

                <div class="wp-section-divider"></div>
                <div class="wp-section-title">Fondo</div>
                <label class="bg-upload">
                    <input type="file" accept="image/png,image/jpeg,image/webp" onchange="uploadBackground(this)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    {{ $template->background_image ? 'Cambiar imagen de fondo' : 'Subir imagen de fondo' }}
                </label>
                @if($template->background_image)
                    <div style="display:flex;align-items:center;gap:8px;margin:6px 12px 0;background:#f6f7f7;border:1px solid #e0e0e0;border-radius:3px;padding:5px 8px;">
                        <img src="{{ asset($template->background_image) }}" style="width:32px;height:32px;border-radius:2px;object-fit:cover;border:1px solid #e0e0e0;">
                        <span style="flex:1;font-size:11px;color:#646970;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Imagen de fondo activa</span>
                        <button onclick="removeBackground()" style="background:none;border:none;color:#d63638;font-size:11px;cursor:pointer;font-weight:600;padding:0;">Quitar</button>
                    </div>
                @endif

                <div class="wp-section-divider"></div>
                <div class="wp-section-title">Variables</div>
                <div class="var-chips">
                    @foreach($variables as $key => $label)
                        <div draggable="true" ondragstart="onVarDragStart(event, '{{ $key }}')" onclick="addVarAsText('{{ $key }}')" class="var-chip" title="{{ $label }}"><code>{{ $key }}</code></div>
                    @endforeach
                </div>
                <div style="padding:6px 12px;font-size:10px;color:#a7aaad;font-style:italic;">Arrastrar al canvas o clic para agregar</div>

            </div>

            {{-- ── Layers panel ── --}}
            <div class="wp-panel hidden" id="panel-layers">
                <div class="wp-section-title">Capas <span style="font-weight:400;text-transform:none;letter-spacing:0;"> — clic para seleccionar</span></div>
                <div class="layer-list" id="layerList"></div>
                <div id="layerEmpty" style="padding:24px 12px;text-align:center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d0d5db" stroke-width="1.3" style="width:28px;height:28px;display:block;margin:0 auto 8px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
                    <p style="font-size:11px;color:#b4b9be;margin:0;">Sin elementos</p>
                </div>
            </div>
        </div>

        {{-- ════ CANVAS ════ --}}
        <div class="wp-canvas-area">
            <div class="wp-canvas-bar">
                <div class="canvas-info">
                    <div class="canvas-dot" id="selectionDot"></div>
                    <span class="canvas-sel" id="selectionInfo">Selecciona un elemento</span>
                </div>
                <div class="canvas-tools">
                    <button onclick="toggleGrid()" id="gridBtn" class="c-btn" title="Cuadrícula" style="font-size:11px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4v16m6-16v16M4 9h16M4 15h16"/></svg>
                    </button>
                    <div style="width:1px;height:14px;background:#e0e0e0;margin:0 2px;"></div>
                    <button onclick="zoomOut()" class="c-btn" title="Alejar">−</button>
                    <span class="c-zoom" id="zoomDisplay">100%</span>
                    <button onclick="zoomIn()" class="c-btn" title="Acercar">+</button>
                    <button onclick="zoomFit()" class="c-btn" title="Ajustar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    </button>
                </div>
            </div>
            <div class="wp-canvas-viewport" id="canvasViewport">
                <div id="canvasWrapper">
                    <canvas id="diplomaCanvas" width="{{ $template->canvas_width }}" height="{{ $template->canvas_height }}"></canvas>
                </div>
            </div>
        </div>

        {{-- ════ RIGHT INSPECTOR ════ --}}
        <div class="wp-inspector" id="propertiesPanel">

            <div id="panelPlaceholder" class="wp-insp-placeholder">
                <div style="width:48px;height:48px;border-radius:4px;background:#f6f7f7;border:1px solid #e0e0e0;display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#c3c4c7" stroke-width="1.3" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                </div>
                <p style="color:#a7aaad;font-size:12px;margin:0;line-height:1.6;text-align:center;">Selecciona un elemento<br>para ver sus propiedades</p>
            </div>

            <div id="panelContent" class="hidden">
                <div class="insp-block-header">
                    <div class="insp-block-icon" id="propIcon">T</div>
                    <div>
                        <div id="propType" style="font-size:13px;font-weight:700;color:#1e1e1e;">Texto</div>
                        <div id="propSubtype" style="font-size:11px;color:#646970;">Elemento de texto</div>
                    </div>
                </div>

                <div class="insp-tabs">
                    <button class="insp-tab active" onclick="switchTab('content',this)">Contenido</button>
                    <button class="insp-tab" onclick="switchTab('style',this)">Estilo</button>
                    <button class="insp-tab" onclick="switchTab('advanced',this)">Avanzado</button>
                </div>

                {{-- ── Contenido ── --}}
                <div class="insp-content" id="tab-content">
                    <div class="insp-panel" id="propSecText">
                        <div class="insp-panel-header">Texto</div>
                        <div class="insp-panel-body">
                            <textarea id="propText" rows="2" oninput="updateProp('text', this.value)" class="wp-textarea" placeholder="Texto del elemento..."></textarea>
                        </div>
                    </div>

                    <div class="insp-panel hidden" id="propSecImage">
                        <div class="insp-panel-header">Imagen</div>
                        <div class="insp-panel-body">
                            <div id="imagePreviewContainer" class="hidden" style="margin-bottom:8px;">
                                <img id="imagePreview" style="width:100%;height:72px;object-fit:contain;border:1px solid #e0e0e0;border-radius:2px;background:#f6f7f7;">
                            </div>
                            <button onclick="replaceImage()" class="wp-btn">Cambiar imagen</button>
                        </div>
                    </div>
                    <div class="insp-panel" id="propSecQR">
                        <div class="insp-panel-header">Código QR</div>
                        <div class="insp-panel-body">
                            <p style="font-size:11.5px;color:#646970;margin:0;line-height:1.5;">El QR se genera automáticamente al emitir el diploma con el enlace de verificación único.</p>
                        </div>
                    </div>
                    <div class="insp-panel" id="propSecFirma" style="display:none;">
                        <div class="insp-panel-header">Cuadro de firma</div>
                        <div class="insp-panel-body">
                            <p style="font-size:11px;color:#646970;margin:0 0 10px;line-height:1.5;">Al emitir el diploma muestra el nombre y cargo del firmante con una línea.</p>
                            <label style="display:flex;align-items:center;gap:6px;margin-bottom:7px;font-size:12px;color:#1e1e1e;cursor:pointer;">
                                <input type="checkbox" id="firmaShowName" checked onchange="updateFirmaProp('mostrar_nombre',this.checked)"> Mostrar nombre
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#1e1e1e;cursor:pointer;">
                                <input type="checkbox" id="firmaShowCargo" checked onchange="updateFirmaProp('mostrar_cargo',this.checked)"> Mostrar cargo
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ── Estilo ── --}}
                <div class="insp-content hidden" id="tab-style">
                    <div class="insp-panel" id="styleSecText">
                        <div class="insp-panel-header">Tipografía</div>
                        <div class="insp-panel-body">
                            <div class="wp-row" style="margin-bottom:8px;">
                                <div>
                                    <label class="wp-label">Fuente</label>
                                    <select id="propFontFamily" onchange="updateProp('fontFamily', this.value)" class="wp-select">
                                        <option value="Arial">Arial</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Verdana">Verdana</option>
                                        <option value="Courier New">Courier New</option>
                                        <option value="Trebuchet MS">Trebuchet MS</option>
                                        <option value="Impact">Impact</option>
                                    </select>
                                </div>
                                <div style="max-width:60px;">
                                    <label class="wp-label">Tamaño</label>
                                    <input type="number" id="propFontSize" value="32" min="1" max="500" oninput="updateProp('fontSize', this.value)" class="wp-input">
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:4px;margin-bottom:8px;">
                                <button onclick="toggleStyle('bold')" id="styleBold" class="fmt-btn" title="Negrita"><strong>B</strong></button>
                                <button onclick="toggleStyle('italic')" id="styleItalic" class="fmt-btn" title="Cursiva"><em>I</em></button>
                                <button onclick="toggleStyle('underline')" id="styleUnderline" class="fmt-btn" title="Subrayado"><u>S</u></button>
                                <div style="width:1px;height:18px;background:#e0e0e0;margin:0 3px;"></div>
                                <label class="wp-label" style="margin:0 4px 0 0;">Color</label>
                                <input type="color" id="propColor" value="#000000" oninput="updateProp('fill', this.value)" class="color-swatch" title="Color de texto">
                            </div>
                            <label class="wp-label">Alineación</label>
                            <div class="align-grp">
                                <button onclick="updateProp('textAlign', 'left')" class="align-btn" data-align="left" title="Izquierda">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'center')" class="align-btn" data-align="center" title="Centro">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 6.75h13.5M9 12h6m-7.5 5.25h9"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'right')" class="align-btn" data-align="right" title="Derecha">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M10.5 12h10.5m-10.5 5.25h16.5"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'justify')" class="align-btn" data-align="justify" title="Justificado">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel" id="styleSecRect">
                        <div class="insp-panel-header">Relleno y borde</div>
                        <div class="insp-panel-body">
                            <div class="wp-row" style="margin-bottom:8px;">
                                <div>
                                    <label class="wp-label">Relleno</label>
                                    <input type="color" id="propFill" value="#ffffff" oninput="updateProp('fill', this.value)" class="color-swatch" style="width:100%;height:34px;">
                                </div>
                                <div>
                                    <label class="wp-label">Borde</label>
                                    <input type="color" id="propStroke" value="#000000" oninput="updateProp('stroke', this.value)" class="color-swatch" style="width:100%;height:34px;">
                                </div>
                            </div>
                            <div class="wp-row">
                                <div>
                                    <label class="wp-label">Grosor</label>
                                    <input type="number" id="propStrokeWidth" value="1" min="0" max="50" oninput="updateProp('strokeWidth', this.value)" class="wp-input">
                                </div>
                                <div>
                                    <label class="wp-label">Radio</label>
                                    <input type="number" id="propRx" value="0" min="0" max="200" oninput="updateProp('rx', this.value)" class="wp-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel" id="styleSecLine">
                        <div class="insp-panel-header">Línea</div>
                        <div class="insp-panel-body">
                            <div class="wp-row">
                                <div>
                                    <label class="wp-label">Color</label>
                                    <input type="color" id="propLineColor" value="#000000" oninput="updateProp('stroke', this.value)" class="color-swatch" style="width:100%;height:34px;">
                                </div>
                                <div>
                                    <label class="wp-label">Grosor</label>
                                    <input type="number" id="propLineWidth" value="2" min="1" max="50" oninput="updateProp('strokeWidth', this.value)" class="wp-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel" id="styleSecFirma" style="display:none;">
                        <div class="insp-panel-header">Texto de la firma</div>
                        <div class="insp-panel-body">
                            <div class="wp-row">
                                <div>
                                    <label class="wp-label">Color</label>
                                    <input type="color" id="firmaColor" value="#1E293B" oninput="updateFirmaProp('fill',this.value)" class="color-swatch" style="width:100%;height:34px;">
                                </div>
                                <div>
                                    <label class="wp-label">Tamaño</label>
                                    <input type="number" id="firmaFontSize" value="11" min="6" max="60" oninput="updateFirmaProp('fontSize',parseInt(this.value))" class="wp-input">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Avanzado ── --}}
                <div class="insp-content hidden" id="tab-advanced">
                    <div class="insp-panel">
                        <div class="insp-panel-header">Posición</div>
                        <div class="insp-panel-body">
                            <div class="wp-row">
                                <div style="position:relative;">
                                    <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:10px;color:#a7aaad;font-weight:700;">X</span>
                                    <input type="number" id="propX" oninput="updateProp('x', this.value)" class="wp-input" style="padding-left:20px;">
                                </div>
                                <div style="position:relative;">
                                    <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:10px;color:#a7aaad;font-weight:700;">Y</span>
                                    <input type="number" id="propY" oninput="updateProp('y', this.value)" class="wp-input" style="padding-left:20px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel">
                        <div class="insp-panel-header">Tamaño</div>
                        <div class="insp-panel-body">
                            <div class="wp-row">
                                <div style="position:relative;">
                                    <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:9px;color:#a7aaad;font-weight:700;">W</span>
                                    <input type="number" id="propW" oninput="updateProp('width', this.value)" class="wp-input" style="padding-left:18px;">
                                </div>
                                <div style="position:relative;">
                                    <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:9px;color:#a7aaad;font-weight:700;">H</span>
                                    <input type="number" id="propH" oninput="updateProp('height', this.value)" class="wp-input" style="padding-left:18px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel">
                        <div class="insp-panel-header">Rotación</div>
                        <div class="insp-panel-body">
                            <input type="range" id="propAngle" min="0" max="360" value="0" oninput="updateProp('angle', this.value)" class="wp-range">
                            <div style="display:flex;justify-content:space-between;font-size:10px;color:#a7aaad;margin-top:2px;">
                                <span>0°</span>
                                <span id="angleDisplay" style="font-weight:700;color:var(--brand);">0°</span>
                                <span>360°</span>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel">
                        <div class="insp-panel-header">Orden de capas</div>
                        <div class="insp-panel-body">
                            <div class="order-btns">
                                <button onclick="moveLayer('front')" class="wp-btn" style="font-size:11px;">↑ Al frente</button>
                                <button onclick="moveLayer('back')" class="wp-btn" style="font-size:11px;">↓ Al fondo</button>
                                <button onclick="moveLayer('up')" class="wp-btn" style="font-size:11px;">↑ Subir</button>
                                <button onclick="moveLayer('down')" class="wp-btn" style="font-size:11px;">↓ Bajar</button>
                            </div>
                        </div>
                    </div>
                    <div class="insp-panel">
                        <div class="insp-panel-header">Acciones</div>
                        <div class="insp-panel-body" style="display:flex;flex-direction:column;gap:6px;">
                            <button onclick="duplicateElement()" class="wp-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                                Duplicar
                            </button>
                            <button onclick="deleteSelected()" class="wp-btn-danger">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                Eliminar elemento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="imageInput" accept="image/*" style="display:none" onchange="handleImageUpload(this)">

    {{-- Context menu --}}
    <div id="contextMenu" class="wp-ctx hidden">
        <button onclick="duplicateElement()" class="ctx-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
            Duplicar
        </button>
        <button onclick="moveLayer('front')" class="ctx-item">Traer al frente</button>
        <button onclick="moveLayer('back')" class="ctx-item">Enviar al fondo</button>
        <div class="ctx-sep"></div>
        <button onclick="deleteSelected()" class="ctx-item ctx-danger">Eliminar</button>
    </div>

    <div id="toastContainer" class="toast-box"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        // ─── Setup ───
        const c = new fabric.Canvas('diplomaCanvas', { preserveObjectStacking: true, selection: true });
        const W = {{ $template->canvas_width }}, H = {{ $template->canvas_height }};
        const existingElements = @json($template->elements);
        const variables = @json($variables);
        let elIdCounter = 0, zoom = 1, showGrid = false, currentTab = 'content';

        // ─── Fabric handles con brand color ───
        const brandColor = getComputedStyle(document.documentElement).getPropertyValue('--brand').trim() || '#1A56B0';
        fabric.Object.prototype.set({
            cornerColor: '#fff',
            cornerStrokeColor: brandColor,
            cornerSize: 9,
            cornerStyle: 'circle',
            borderColor: brandColor,
            borderScaleFactor: 1.5,
            transparentCorners: false,
            padding: 5,
        });

        // ─── Undo/Redo ───
        let history = [], historyIndex = -1;
        const MAX_HISTORY = 50;

        function saveState() {
            const json = JSON.stringify(c.toJSON(['_elId','_tipo','_src']));
            if (historyIndex < history.length - 1) history = history.slice(0, historyIndex + 1);
            history.push(json);
            if (history.length > MAX_HISTORY) history.shift();
            historyIndex = history.length - 1;
            updateUndoButtons();
        }
        function undo() {
            if (historyIndex <= 0) return;
            historyIndex--;
            c.loadFromJSON(JSON.parse(history[historyIndex]), () => { c.renderAll(); refreshLayers(); });
            updateUndoButtons(); toast('Deshecho','info');
        }
        function redo() {
            if (historyIndex >= history.length - 1) return;
            historyIndex++;
            c.loadFromJSON(JSON.parse(history[historyIndex]), () => { c.renderAll(); refreshLayers(); });
            updateUndoButtons(); toast('Rehecho','info');
        }
        function updateUndoButtons() {
            document.getElementById('undoBtn').disabled = historyIndex <= 0;
            document.getElementById('redoBtn').disabled = historyIndex >= history.length - 1;
        }
        function autoSave() { saveState(); }

        // ─── Background ───
        // Uses native Image loading (no CORS/crossOrigin needed for same-origin files).
        function setBackground(url, onDone) {
            const img = new Image();
            img.onload = function() {
                const fImg = new fabric.Image(img, {
                    left: 0, top: 0,
                    originX: 'left', originY: 'top',
                    scaleX: W / (img.naturalWidth  || img.width  || W),
                    scaleY: H / (img.naturalHeight || img.height || H),
                    selectable: false,
                    evented: false,
                });
                c.backgroundImage = fImg;
                c.renderAll();
                if (onDone) onDone();
            };
            img.onerror = function() {
                toast('No se pudo cargar la imagen de fondo', 'error');
            };
            // cache-bust so stale image doesn't interfere
            img.src = url + (url.includes('?') ? '&' : '?') + '_t=' + Date.now();
        }

        @if($template->background_image)
        setBackground('{{ asset($template->background_image) }}', saveState);
        @else
        saveState();
        @endif

        // ─── Load existing elements ───
        existingElements.forEach(el => createEl(el));
        refreshLayers();

        function createEl(el) {
            const config = el.config_json || {}, id = el.id || 'el_'+(++elIdCounter);
            let obj, tipo=el.tipo;
            if (tipo === 'variable') {
                tipo = 'text';
                config.text = '@{{' + (el.variable || 'variable') + '}}';
            }
            switch (tipo) {
                case 'text':
                    obj = new fabric.Textbox(config.text||'Texto',{left:el.x,top:el.y,width:el.width,height:el.height,fontSize:config.fontSize||32,fill:config.fill||'#000000',textAlign:config.textAlign||'left',fontFamily:config.fontFamily||'Arial',fontStyle:config.italic?'italic':'normal',fontWeight:config.bold?'bold':'normal',underline:config.underline||false,splitByGrapheme:true}); break;
                case 'qr':
                    obj = new fabric.Rect({left:el.x,top:el.y,width:el.width||120,height:el.height||120,fill:'#f8f8f8',stroke:'#999',strokeWidth:1,strokeDashArray:[5,3],rx:2,ry:2}); break;
                case 'firma': {
                    const cfg=el.config_json&&typeof el.config_json==='string'?JSON.parse(el.config_json):el.config_json||{};
                    obj=new fabric.Rect({left:el.x,top:el.y,width:el.width||220,height:el.height||80,fill:'#F0F9FF',stroke:'#0EA5E9',strokeWidth:1,strokeDashArray:[4,3],rx:3,ry:3});
                    obj._mostrarNombre=cfg.mostrar_nombre??true; obj._mostrarCargo=cfg.mostrar_cargo??true;
                    obj._firmaColor=cfg.fill||'#1E293B'; obj._firmaFontSize=cfg.fontSize||11;
                    break; }
                case 'rect':
                    obj = new fabric.Rect({left:el.x,top:el.y,width:el.width,height:el.height,fill:config.fill||'transparent',stroke:config.stroke||'#000',strokeWidth:config.strokeWidth||1,rx:config.rx||0,ry:config.ry||0}); break;
                case 'line':
                    obj = new fabric.Line([el.x,el.y,el.x+el.width,el.y+el.height],{stroke:config.stroke||'#000',strokeWidth:config.strokeWidth||2}); break;
                case 'image':
                    if(config.src){fabric.Image.fromURL(config.src,function(img){img.set({left:el.x,top:el.y,scaleX:el.width/img.width,scaleY:el.height/img.height});img._elId=id;img._tipo='image';img._src=config.src;img._path=config.path||'';c.add(img);c.renderAll();refreshLayers();},{crossOrigin:'anonymous'});return;}
            }
            if(obj){obj._elId=id;obj._tipo=el.tipo;obj._variable=el.variable||null;c.add(obj);c.renderAll();}
        }

        function uid(){ return 'el_'+(++elIdCounter); }

        function makeObj(tipo, opts={}) {
            let obj; const x=opts.x||100, y=opts.y||100;
            switch(tipo) {
                case 'text': obj=new fabric.Textbox(opts.text||'Texto',{left:x,top:y,width:300,height:60,fontSize:32,fill:'#000',splitByGrapheme:true}); break;
                case 'qr': obj=new fabric.Rect({left:x,top:y,width:120,height:120,fill:'#f8f8f8',stroke:'#999',strokeWidth:1,strokeDashArray:[5,3],rx:2,ry:2}); break;
                case 'firma': obj=new fabric.Rect({left:x,top:y,width:220,height:80,fill:'#F0F9FF',stroke:'#0EA5E9',strokeWidth:1,strokeDashArray:[4,3],rx:3,ry:3}); obj._mostrarNombre=true; obj._mostrarCargo=true; obj._firmaColor='#1E293B'; obj._firmaFontSize=11; break;
                case 'rect': obj=new fabric.Rect({left:x,top:y,width:200,height:100,fill:'transparent',stroke:'#000',strokeWidth:1}); break;
                case 'line': obj=new fabric.Line([x,y,x+200,y],{stroke:'#000',strokeWidth:2}); break;
                case 'image': document.getElementById('imageInput').click(); return null;
            }
            if(obj){obj._elId=uid();obj._tipo=tipo;c.add(obj);c.setActiveObject(obj);c.renderAll();autoSave();refreshLayers();}
            return obj;
        }

        function addText()     { makeObj('text');     toast('Texto añadido','success'); }
        function addVarAsText(key) {
            const obj=makeObj('text',{text:'@{{'+key+'}}'});
            toast('Variable añadida: @{{'+key+'}}','success');
        }
        function addQr()       { makeObj('qr');       toast('QR añadido','success'); }
        function addFirma()    { makeObj('firma');    toast('Cuadro de firma añadido','success'); }
        function addRect()     { makeObj('rect');     toast('Figura añadida','success'); }
        function addLine()     { makeObj('line');     toast('Línea añadida','success'); }
        function addImage()    { document.getElementById('imageInput').click(); }

        function handleImageUpload(input) {
            const file=input.files[0]; if(!file) return;
            toast('Subiendo imagen...','info');
            const fd=new FormData();
            fd.append('image',file);
            fd.append('_token','{{ csrf_token() }}');
            fetch('{{ route('admin.templates.upload-image', $template) }}',{method:'POST',body:fd})
            .then(r=>r.json()).then(d=>{
                if(!d.url){toast('Error al subir imagen','error');return;}
                fabric.Image.fromURL(d.url,function(img){
                    // Center image on canvas at a sensible initial size
                    const maxW=Math.min(600, W*0.5), maxH=Math.min(600, H*0.5);
                    const s=Math.min(maxW/img.width, maxH/img.height, 1);
                    img.set({
                        left: W/2 - (img.width*s)/2,
                        top:  H/2 - (img.height*s)/2,
                        scaleX:s, scaleY:s
                    });
                    img._elId=uid();img._tipo='image';img._src=d.url;img._path=d.path;
                    c.add(img);c.setActiveObject(img);c.renderAll();autoSave();refreshLayers();
                    toast('Imagen añadida','success');
                },{crossOrigin:'anonymous'});
            }).catch(()=>toast('Error de red al subir imagen','error'));
            input.value='';
        }

        // ─── Drag & Drop ───
        function onDragStart(e,tipo){
            e.dataTransfer.setData('text/plain',tipo);
            e.dataTransfer.effectAllowed='copy';
            e.target.classList.add('dragging');
            setTimeout(()=>e.target.classList.remove('dragging'),0);
        }
        function onVarDragStart(e,key){
            e.dataTransfer.setData('text/plain','var::'+key);
            e.dataTransfer.effectAllowed='copy';
        }
        document.getElementById('canvasViewport').addEventListener('dragover',e=>{e.preventDefault();e.dataTransfer.dropEffect='copy';});
        document.getElementById('canvasViewport').addEventListener('drop',function(e){
            e.preventDefault();
            const tipo=e.dataTransfer.getData('text/plain'); if(!tipo) return;
            const rect=c.getElement().getBoundingClientRect(), scale=rect.width/W;
            const x=(e.clientX-rect.left)/scale, y=(e.clientY-rect.top)/scale;
            if(tipo==='image'){document.getElementById('imageInput').click();return;}
            if(tipo.startsWith('var::')){
                const key=tipo.slice(5);
                makeObj('text',{x,y,text:'@{{'+key+'}}'});
                toast('Variable añadida: @{{'+key+'}}','success');
                return;
            }
            makeObj(tipo,{x,y,text:tipo==='text'?'Texto':undefined});
            toast('Elemento agregado','success');
        });

        // ─── Selection & Properties ───
        c.on('selection:created', e=>showProps(e.selected[0]));
        c.on('selection:updated', e=>showProps(e.selected[0]));
        c.on('selection:cleared', ()=>hideProps());

        // Dibuja la línea y etiqueta de los elementos tipo 'firma' encima del canvas
        c.on('after:render', function() {
            const ctx = c.contextContainer;
            const vt  = c.viewportTransform || [1,0,0,1,0,0];
            c.getObjects().forEach(function(o) {
                if(o._tipo !== 'firma') return;
                const w = o.width  * (o.scaleX || 1);
                const h = o.height * (o.scaleY || 1);
                const lx = o.left, ly = o.top;
                ctx.save();
                ctx.transform(vt[0], vt[1], vt[2], vt[3], vt[4], vt[5]);
                // Línea de firma
                ctx.beginPath();
                ctx.strokeStyle = '#0369A1';
                ctx.lineWidth = 1;
                ctx.moveTo(lx + w * 0.06, ly + h * 0.52);
                ctx.lineTo(lx + w * 0.94, ly + h * 0.52);
                ctx.stroke();
                // Etiqueta
                const fs = Math.max(8, h * 0.16);
                ctx.fillStyle = '#0369A1';
                ctx.font = 'bold ' + fs + 'px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('✍ FIRMA', lx + w / 2, ly + h * 0.36);
                ctx.restore();
            });
        });
        c.on('object:moving', e=>{
            const o=e.target;
            document.getElementById('selectionInfo').textContent=`X:${Math.round(o.left)} Y:${Math.round(o.top)} W:${Math.round(o.width*(o.scaleX||1))} H:${Math.round(o.height*(o.scaleY||1))}`;
            if(currentElId) updateInputs(o);
        });
        c.on('object:scaling', e=>{
            const o=e.target;
            document.getElementById('selectionInfo').textContent=`X:${Math.round(o.left)} Y:${Math.round(o.top)} W:${Math.round(o.width*(o.scaleX||1))} H:${Math.round(o.height*(o.scaleY||1))}`;
            if(currentElId) updateInputs(o);
        });
        c.on('object:rotating', e=>{
            const o=e.target;
            document.getElementById('selectionInfo').textContent=`X:${Math.round(o.left)} Y:${Math.round(o.top)} ↻${Math.round(o.angle)}°`;
        });
        c.on('object:modified', ()=>{if(currentElId)updateInputs(c.getActiveObject());autoSave();refreshLayers();});

        let currentElId = null;

        function showProps(obj) {
            currentElId = obj._elId;
            document.getElementById('panelPlaceholder').classList.add('hidden');
            document.getElementById('panelContent').classList.remove('hidden');
            document.getElementById('selectionInfo').textContent = `X:${Math.round(obj.left)} Y:${Math.round(obj.top)} W:${Math.round(obj.width)} H:${Math.round(obj.height)}`;
            document.getElementById('selectionDot').style.background = 'var(--brand)';

            const tipo=obj._tipo||'';
            const names={text:'Texto',variable:'Variable',qr:'Código QR',rect:'Figura',line:'Línea',image:'Imagen',firma:'Cuadro de firma'};
            document.getElementById('propType').textContent=names[tipo]||tipo;
            document.getElementById('propSubtype').textContent=tipo==='image'?'Imagen insertada':tipo==='firma'?'Firma del diploma':'Elemento visual';
            const icons={text:'<strong style="font-size:15px">T</strong>',qr:'<span style="font-size:13px">▦</span>',rect:'<span style="font-size:15px">■</span>',line:'<span style="font-size:15px">—</span>',image:'<span style="font-size:14px">🖼</span>',firma:'<span style="font-size:13px">✍</span>'};
            document.getElementById('propIcon').innerHTML=icons[tipo]||'•';

            document.getElementById('propSecText').style.display=tipo==='text'?'':'none';
            document.getElementById('propSecImage').style.display=tipo==='image'?'':'none';
            document.getElementById('propSecQR').style.display=tipo==='qr'?'':'none';
            document.getElementById('propSecFirma').style.display=tipo==='firma'?'':'none';
            document.getElementById('styleSecText').style.display=tipo==='text'?'':'none';
            document.getElementById('styleSecRect').style.display=tipo==='rect'?'':'none';
            document.getElementById('styleSecLine').style.display=tipo==='line'?'':'none';
            document.getElementById('styleSecFirma').style.display=tipo==='firma'?'':'none';

            if(tipo==='image'&&obj._src){document.getElementById('imagePreviewContainer').classList.remove('hidden');document.getElementById('imagePreview').src=obj._src;}
            else document.getElementById('imagePreviewContainer').classList.add('hidden');

            // Always reset to Contenido tab when selecting a new element
            document.querySelectorAll('.insp-tab').forEach((t,i)=>t.classList.toggle('active',i===0));
            document.querySelectorAll('.insp-content').forEach(p=>{p.classList.remove('active');p.classList.add('hidden');});
            document.getElementById('tab-content').classList.remove('hidden');
            document.getElementById('tab-content').classList.add('active');
            currentTab='content';

            updateInputs(obj);
        }

        function hideProps() {
            currentElId=null;
            document.getElementById('panelPlaceholder').classList.remove('hidden');
            document.getElementById('panelContent').classList.add('hidden');
            document.getElementById('selectionInfo').textContent='Selecciona un elemento';
            document.getElementById('selectionDot').style.background='#b4b9be';
        }

        function updateInputs(obj) {
            if(!obj) return;
            document.getElementById('propX').value=Math.round(obj.left);
            document.getElementById('propY').value=Math.round(obj.top);
            document.getElementById('propW').value=Math.round(obj.width*(obj.scaleX||1));
            document.getElementById('propH').value=Math.round(obj.height*(obj.scaleY||1));
            document.getElementById('propAngle').value=Math.round(obj.angle||0);
            document.getElementById('angleDisplay').textContent=Math.round(obj.angle||0)+'°';
            if(obj._tipo==='text'){
                document.getElementById('propText').value=obj.text||'';
                document.getElementById('propFontSize').value=obj.fontSize||32;
                document.getElementById('propFontFamily').value=obj.fontFamily||'Arial';
                document.getElementById('propColor').value=obj.fill||'#000000';
                document.getElementById('styleBold').classList.toggle('active',obj.fontWeight==='bold');
                document.getElementById('styleItalic').classList.toggle('active',obj.fontStyle==='italic');
                document.getElementById('styleUnderline').classList.toggle('active',!!obj.underline);
                document.querySelectorAll('.align-btn').forEach(b=>b.classList.toggle('active',b.dataset.align===(obj.textAlign||'left')));
            }
            if(obj._tipo==='rect'){document.getElementById('propFill').value=obj.fill||'#ffffff';document.getElementById('propStroke').value=obj.stroke||'#000000';document.getElementById('propStrokeWidth').value=obj.strokeWidth||1;document.getElementById('propRx').value=obj.rx||0;}
            if(obj._tipo==='line'){document.getElementById('propLineColor').value=obj.stroke||'#000000';document.getElementById('propLineWidth').value=obj.strokeWidth||2;}
            if(obj._tipo==='firma'){
                document.getElementById('firmaShowName').checked=obj._mostrarNombre??true;
                document.getElementById('firmaShowCargo').checked=obj._mostrarCargo??true;
                document.getElementById('firmaColor').value=obj._firmaColor||'#1E293B';
                document.getElementById('firmaFontSize').value=obj._firmaFontSize||11;
            }
        }

        function updateFirmaProp(prop, value) {
            const obj = c.getActiveObject(); if (!obj || obj._tipo !== 'firma') return;
            if(prop==='mostrar_nombre') obj._mostrarNombre=value;
            else if(prop==='mostrar_cargo') obj._mostrarCargo=value;
            else if(prop==='fill') { obj._firmaColor=value; obj.set('stroke', value); }
            else if(prop==='fontSize') obj._firmaFontSize=value;
            c.requestRenderAll();
            pushUndo();
        }

        function updateProp(prop, value) {
            const obj = c.getActiveObject(); if (!obj) return;
            switch(prop) {
                case 'text':
                    obj.set('text', String(value));
                    break;
                case 'textAlign':
                    obj.set('textAlign', value);
                    document.querySelectorAll('.align-btn').forEach(b=>b.classList.toggle('active', b.dataset.align===value));
                    break;
                case 'x': obj.set('left', parseFloat(value)||0); break;
                case 'y': obj.set('top',  parseFloat(value)||0); break;
                case 'angle': {
                    const a = parseFloat(value)||0;
                    obj.set('angle', a);
                    document.getElementById('angleDisplay').textContent = Math.round(a)+'°';
                    break;
                }
                case 'width': {
                    const w = Math.max(10, parseFloat(value)||10);
                    if((obj.scaleX||1) !== 1){ obj.set('scaleX',1); }
                    obj.set('width', w);
                    if(obj.initDimensions) obj.initDimensions();
                    break;
                }
                case 'height': {
                    const h = Math.max(10, parseFloat(value)||10);
                    if((obj.scaleY||1) !== 1){ obj.set('scaleY',1); }
                    obj.set('height', h);
                    if(obj.initDimensions) obj.initDimensions();
                    break;
                }
                case 'fontSize': {
                    const fs = parseFloat(value);
                    if(!isNaN(fs) && fs > 0){ obj.set('fontSize', fs); }
                    break;
                }
                case 'strokeWidth':
                case 'rx':
                case 'ry': {
                    const n = parseFloat(value);
                    if(!isNaN(n)) obj.set(prop, n);
                    break;
                }
                default:
                    obj.set(prop, value);
            }
            if(obj.initDimensions) obj.initDimensions();
            c.requestRenderAll();
            if(currentElId) updateInputs(obj);
        }

        function toggleStyle(s) {
            const obj = c.getActiveObject(); if(!obj) return;
            const btn = {bold:'styleBold', italic:'styleItalic', underline:'styleUnderline'}[s];
            const el  = document.getElementById(btn);
            if(s==='bold')      { const v=obj.fontWeight==='bold'?'normal':'bold'; obj.set('fontWeight',v); el.classList.toggle('active'); }
            else if(s==='italic')    { const v=obj.fontStyle==='italic'?'normal':'italic'; obj.set('fontStyle',v); el.classList.toggle('active'); }
            else if(s==='underline') { obj.set('underline',!obj.underline); el.classList.toggle('active'); }
            if(obj.initDimensions) obj.initDimensions();
            c.requestRenderAll();
        }

        function switchTab(tab,btn){
            currentTab=tab;
            document.querySelectorAll('.insp-tab').forEach(t=>t.classList.remove('active'));
            // Remove both 'active' and add 'hidden' to hide all panels
            document.querySelectorAll('.insp-content').forEach(p=>{p.classList.remove('active');p.classList.add('hidden');});
            btn.classList.add('active');
            const target=document.getElementById('tab-'+tab);
            target.classList.remove('hidden');
            target.classList.add('active');
        }

        function switchPanel(panel){
            document.querySelectorAll('.wp-tab').forEach(t=>t.classList.toggle('active',t.dataset.panel===panel));
            document.querySelectorAll('.wp-panel').forEach(p=>p.classList.toggle('hidden',p.id!=='panel-'+panel));
        }

        function deleteSelected(){
            const obj=c.getActiveObject(); if(!obj) return;
            c.remove(obj);c.discardActiveObject();hideProps();c.renderAll();autoSave();refreshLayers();
            toast('Elemento eliminado','info');
        }

        function duplicateElement(){
            const obj=c.getActiveObject(); if(!obj) return;
            obj.clone(function(clone){
                clone.set({left:(clone.left||0)+20,top:(clone.top||0)+20});
                clone._elId=uid();clone._tipo=obj._tipo;clone._variable=obj._variable;clone._src=obj._src;
                c.add(clone);c.setActiveObject(clone);c.renderAll();autoSave();refreshLayers();
                toast('Duplicado','success');
            });
        }

        function moveLayer(dir){
            const obj=c.getActiveObject(); if(!obj) return;
            if(dir==='up') c.bringForward(obj); else if(dir==='down') c.sendBackwards(obj);
            else if(dir==='front') c.bringToFront(obj); else if(dir==='back') c.sendToBack(obj);
            c.renderAll();autoSave();refreshLayers();
        }

        function replaceImage(){ document.getElementById('imageInput').click(); }

        function insertVariable(key){
            const obj=c.getActiveObject();
            if(obj&&(obj._tipo==='text'||obj._tipo==='variable')){obj.set('text',(obj.text||'')+'@{{'+key+'}}');c.renderAll();autoSave();toast('Variable insertada','success');}
            else toast('Selecciona un texto primero','info');
        }

        // ─── Layers ───
        function refreshLayers(){
            const list=document.getElementById('layerList'),empty=document.getElementById('layerEmpty'),objs=c.getObjects();
            if(!objs.length){list.innerHTML='';empty.classList.remove('hidden');return;}
            empty.classList.add('hidden');
            const names={text:'Texto',qr:'QR',rect:'Figura',line:'Línea',image:'Imagen'};
            const icons={text:'T',qr:'▦',rect:'■',line:'—',image:'🖼'};
            const sel=c.getActiveObject();
            list.innerHTML=objs.slice().reverse().map((o)=>{
                const t=o._tipo||'text',active=sel&&sel._elId===o._elId?'active':'';
                return `<div class="layer-item ${active}" onclick="selectLayer('${o._elId}')">
                    <span class="layer-icon">${icons[t]||'•'}</span>
                    <span class="layer-name">${names[t]||t}</span>
                    <span class="layer-del" onclick="event.stopPropagation();c.remove(o);c.renderAll();autoSave();refreshLayers();hideProps();" title="Eliminar">✕</span>
                </div>`;
            }).join('');
        }

        function selectLayer(id){
            const objs=c.getObjects().filter(o=>o._elId===id);
            if(objs.length){c.setActiveObject(objs[0]);c.renderAll();refreshLayers();}
        }

        // ─── Zoom ───
        // Strategy: keep canvas DOM at W×H (Fabric's internal space unchanged),
        // CSS-scale c.wrapperEl so pointer math via getBoundingClientRect() stays correct,
        // set #canvasWrapper to the VISUAL size so flex centering works.
        function applyZoom() {
            const outer = document.getElementById('canvasWrapper');
            outer.style.width  = Math.round(W * zoom) + 'px';
            outer.style.height = Math.round(H * zoom) + 'px';
            c.wrapperEl.style.transform       = `scale(${zoom})`;
            c.wrapperEl.style.transformOrigin = 'top left';
            c.calcOffset(); // sync Fabric's pointer-offset with new visual bounds
            document.getElementById('zoomDisplay').textContent = Math.round(zoom*100)+'%';
        }
        function zoomIn()  { zoom = Math.min(+(zoom+.1).toFixed(2), 3);   applyZoom(); }
        function zoomOut() { zoom = Math.max(+(zoom-.1).toFixed(2), 0.1);  applyZoom(); }
        function zoomFit() {
            const vp = document.getElementById('canvasViewport');
            const sx = (vp.clientWidth  - 64) / W;
            const sy = (vp.clientHeight - 64) / H;
            zoom = +Math.min(sx, sy, 2).toFixed(2);
            applyZoom();
        }

        // ─── Grid ───
        function toggleGrid(){
            showGrid=!showGrid;
            document.getElementById('canvasViewport').classList.toggle('canvas-grid',showGrid);
            document.getElementById('gridBtn').classList.toggle('active',showGrid);
        }

        // ─── Context menu ───
        c.on('mouse:down',e=>{ if(e.button!==3) hideCtx(); });
        c.on('mouse:down',function(o){
            if(o.button===3){const ev=o.e;ev.preventDefault();const t=c.findTarget(ev,false);if(t){c.setActiveObject(t);c.renderAll();showCtx(ev.clientX,ev.clientY);}else hideCtx();}
        });
        document.addEventListener('click',e=>{if(!e.target.closest('#contextMenu'))hideCtx();});
        document.addEventListener('contextmenu',e=>{if(e.target.closest('.wp-editor'))e.preventDefault();});

        function showCtx(x,y){
            const m=document.getElementById('contextMenu');
            m.classList.remove('hidden');m.style.left=m.style.top='0px';
            const r=m.getBoundingClientRect(),vw=window.innerWidth,vh=window.innerHeight;
            m.style.left=Math.max(8,Math.min(x,vw-r.width-8))+'px';
            m.style.top=Math.max(8,Math.min(y,vh-r.height-8))+'px';
        }
        function hideCtx(){ document.getElementById('contextMenu').classList.add('hidden'); }

        // ─── Toast ───
        function toast(msg,type='info'){
            const box=document.getElementById('toastContainer'),el=document.createElement('div');
            el.className='toast toast-'+type;el.textContent=msg;box.appendChild(el);
            setTimeout(()=>{el.style.animation='toastOut .2s ease forwards';setTimeout(()=>el.remove(),200);},2500);
        }

        // ─── Keyboard ───
        document.addEventListener('keydown',function(e){
            if(e.target.tagName==='INPUT'||e.target.tagName==='TEXTAREA'||e.target.tagName==='SELECT') return;
            if(e.ctrlKey&&e.key==='z'&&!e.shiftKey){e.preventDefault();undo();}
            else if(e.ctrlKey&&(e.key==='y'||(e.key==='z'&&e.shiftKey))){e.preventDefault();redo();}
            else if(e.key==='Delete'||e.key==='Backspace') deleteSelected();
            else if(e.ctrlKey&&e.key==='d'){e.preventDefault();duplicateElement();}
            else if(e.ctrlKey&&e.key==='s'){e.preventDefault();saveCanvas();}
            else{const o=c.getActiveObject(),n=e.shiftKey?10:1;if(o){
                if(e.key==='ArrowUp'){o.set('top',(o.top||0)-n);c.renderAll();updateInputs(o);}
                else if(e.key==='ArrowDown'){o.set('top',(o.top||0)+n);c.renderAll();updateInputs(o);}
                else if(e.key==='ArrowLeft'){o.set('left',(o.left||0)-n);c.renderAll();updateInputs(o);}
                else if(e.key==='ArrowRight'){o.set('left',(o.left||0)+n);c.renderAll();updateInputs(o);}
            }}
        });

        // ─── Background AJAX ───
        function uploadBackground(input){
            const file=input.files[0]; if(!file) return;
            const fd=new FormData();fd.append('image',file);fd.append('_token','{{ csrf_token() }}');
            toast('Subiendo fondo...','info');
            fetch('{{ route('admin.templates.upload-background', $template) }}',{method:'POST',body:fd})
            .then(r=>r.json()).then(d=>{
                if(d.url){
                    setBackground(d.url, function(){
                        autoSave();
                        toast('Fondo actualizado','success');
                    });
                } else {
                    toast('Error al subir el fondo','error');
                }
            }).catch(()=>toast('Error de red','error'));
        }
        function removeBackground(){
            fetch('{{ route('admin.templates.remove-background', $template) }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}})
            .then(()=>{
                c.backgroundImage = null;
                c.renderAll();
                autoSave();
                toast('Fondo eliminado','info');
            });
        }

        // ─── Save ───
        function getElements(){
            return c.getObjects().map((o,i)=>{
                if(o._tipo==='image'&&(!o._src||o._src.startsWith('data:'))) return null;
                let tipo = o._tipo;
                if (tipo === 'variable') tipo = 'text';
                let cfg={};
                if(tipo==='text') cfg={text:o.text,fontSize:o.fontSize,fill:o.fill,fontFamily:o.fontFamily,textAlign:o.textAlign,bold:o.fontWeight==='bold',italic:o.fontStyle==='italic',underline:o.underline};
                else if(tipo==='rect') cfg={fill:o.fill,stroke:o.stroke,strokeWidth:o.strokeWidth,rx:o.rx,ry:o.ry};
                else if(tipo==='line') cfg={stroke:o.stroke,strokeWidth:o.strokeWidth};
                else if(tipo==='image') cfg={src:o._src||'',path:o._path||''};
                else if(tipo==='firma') cfg={mostrar_nombre:o._mostrarNombre??true,mostrar_cargo:o._mostrarCargo??true,fontSize:o._firmaFontSize||11,fill:o._firmaColor||'#1E293B'};
                return{id:o._elId&&!o._elId.toString().startsWith('el_')?parseInt(o._elId):null,tipo:tipo,x:Math.round(o.left),y:Math.round(o.top),width:Math.round(o.width*(o.scaleX||1)),height:Math.round(o.height*(o.scaleY||1)),config_json:JSON.stringify(cfg),orden:i+1};
            }).filter(Boolean);
        }

        function saveCanvas(){
            const btn=document.getElementById('saveBtn');
            btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;animation:spin .6s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Guardando...';
            btn.disabled=true;
            fetch('{{ route('admin.templates.save-elements', $template) }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({elements:getElements()})})
            .then(r=>r.json()).then(d=>{
                if(d.success){toast('Guardado correctamente','success');btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardado';}
                else{toast('Error: '+(d.message||'desconocido'),'error');btn.innerHTML='Guardar';}
                setTimeout(()=>{btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardar';btn.disabled=false;},2000);
            }).catch(e=>{toast('Error de red','error');btn.innerHTML='Guardar';btn.disabled=false;});
        }

        function previewCanvas(){
            const url=c.toDataURL({format:'png',multiplier:2});
            const w=window.open('','_blank');
            w.document.write(`<html><head><title>Vista previa — {{ $template->nombre }}</title><style>body{margin:0;background:#1e1e1e;display:flex;align-items:center;justify-content:center;min-height:100vh;}img{max-width:95vw;max-height:95vh;box-shadow:0 8px 40px rgba(0,0,0,.5);}</style></head><body><img src="${url}"></body></html>`);
        }

        // ─── Init ───
        setTimeout(zoomFit,100);
        window.addEventListener('resize',zoomFit);
    </script>
</x-app-layout>
