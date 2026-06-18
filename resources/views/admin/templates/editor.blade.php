<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-lg text-gray-800">{{ $template->nombre }}</h2>
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ $template->canvas_width }}×{{ $template->canvas_height }}</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="undo()" id="undoBtn" disabled title="Deshacer (Ctrl+Z)" class="header-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                </button>
                <button onclick="redo()" id="redoBtn" disabled title="Rehacer (Ctrl+Shift+Z)" class="header-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3"/></svg>
                </button>
                <div class="w-px h-5 bg-gray-300"></div>
                <button onclick="previewCanvas()" class="header-btn" title="Vista previa">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
                <button onclick="saveCanvas()" id="saveBtn" class="header-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Guardar
                </button>
                <a href="{{ route('admin.templates.show', $template) }}" class="header-btn text-gray-500">×</a>
            </div>
        </div>
    </x-slot>

    <div class="editor-layout" id="app">
        {{-- LEFT PANEL --}}
        <div class="panel-left">
            <div class="panel-tabs">
                <button class="panel-tab active" data-panel="elements" onclick="switchPanel('elements')">Elementos</button>
                <button class="panel-tab" data-panel="layers" onclick="switchPanel('layers')">Capas</button>
            </div>

            {{-- Elements Panel --}}
            <div class="panel-body" id="panel-elements">
                <div class="px-3 py-2">
                    <p class="text-xs text-gray-400 mb-2">Arrastra al lienzo o haz clic</p>
                </div>
                <div class="element-grid px-3">
                    <div draggable="true" ondragstart="onDragStart(event, 'text')" onclick="addText()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-4.5 3h9M8.25 21h7.5"/></svg>
                        <span>Texto</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'variable')" onclick="addVariable()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2-8h.01M15 16h.01M12 8h.01M9 20h6a2 2 0 002-2V6a2 2 0 00-2-2H9a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Variable</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'qr')" onclick="addQr()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM3.75 15.375c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM15 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/></svg>
                        <span>QR</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'rect')" onclick="addRect()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.75" y="3.75" width="16.5" height="16.5" rx="1.5"/></svg>
                        <span>Figura</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'line')" onclick="addLine()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5"/></svg>
                        <span>Línea</span>
                    </div>
                    <div draggable="true" ondragstart="onDragStart(event, 'image')" onclick="addImage()" class="element-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                        <span>Imagen</span>
                    </div>
                </div>

                <div class="px-3 mt-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Fondo</div>
                    <label class="bg-upload-btn">
                        <input type="file" accept="image/png,image/jpeg,image/webp" onchange="uploadBackground(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>{{ $template->background_image ? 'Cambiar fondo' : 'Subir fondo' }}</span>
                    </label>
                    @if($template->background_image)
                        <div class="flex items-center gap-2 mt-1">
                            <img src="{{ Storage::url($template->background_image) }}" class="w-8 h-8 rounded object-cover border">
                            <button onclick="removeBackground()" class="text-xs text-red-500 hover:underline">Eliminar</button>
                        </div>
                    @endif
                </div>

                <div class="px-3 mt-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Variables</div>
                    <div class="space-y-0.5">
                        @foreach($variables as $key => $label)
                            <div onclick="insertVariable('{{ $key }}')" class="var-chip" title="Insertar {{ $label }}">
                                <code>{{ $key }}</code>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Layers Panel --}}
            <div class="panel-body hidden" id="panel-layers">
                <div class="px-3 py-2">
                    <p class="text-xs text-gray-400 mb-1">Arrastra para reordenar capas</p>
                </div>
                <div class="layer-list" id="layerList"></div>
                <div class="px-3 mt-2" id="layerEmpty">
                    <p class="text-xs text-gray-400 text-center py-4">No hay elementos aún</p>
                </div>
            </div>
        </div>

        {{-- CANVAS --}}
        <div class="canvas-area">
            <div class="canvas-toolbar">
                <div class="flex items-center gap-2">
                    <span id="selectionInfo" class="text-xs text-gray-400">Selecciona un elemento</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleGrid()" id="gridBtn" class="toolbar-btn" title="Mostrar cuadrícula">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4v16m6-16v16M4 9h16M4 15h16"/></svg>
                    </button>
                    <button onclick="zoomOut()" class="toolbar-btn" title="Alejar">−</button>
                    <span id="zoomDisplay" class="text-xs font-mono min-w-[3rem] text-center">100%</span>
                    <button onclick="zoomIn()" class="toolbar-btn" title="Acercar">+</button>
                    <button onclick="zoomFit()" class="toolbar-btn" title="Ajustar a pantalla">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    </button>
                </div>
            </div>
            <div class="canvas-viewport" id="canvasViewport">
                <div id="canvasWrapper">
                    <canvas id="diplomaCanvas" width="{{ $template->canvas_width }}" height="{{ $template->canvas_height }}"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="panel-right" id="propertiesPanel">
            <div id="panelPlaceholder" class="panel-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="w-16 h-16 text-gray-200"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                <p class="text-gray-400 text-sm mt-3">Selecciona un elemento<br>para editarlo</p>
            </div>
            <div id="panelContent" class="hidden">
                <div class="prop-header">
                    <span id="propIcon" class="prop-element-icon">Aa</span>
                    <div>
                        <div id="propType" class="text-sm font-medium">Texto</div>
                        <div id="propSubtype" class="text-xs text-gray-400">Elemento de texto</div>
                    </div>
                </div>
                <div class="prop-tabs">
                    <button class="prop-tab active" onclick="switchTab('content', this)">Contenido</button>
                    <button class="prop-tab" onclick="switchTab('style', this)">Estilo</button>
                    <button class="prop-tab" onclick="switchTab('advanced', this)">Avanzado</button>
                </div>

                <div class="prop-content" id="tab-content">
                    <div class="prop-section" id="propSecText">
                        <label class="prop-label">Texto</label>
                        <textarea id="propText" rows="2" oninput="updateProp('text', this.value)" class="prop-input" placeholder="Escribe tu texto aquí..."></textarea>
                    </div>
                    <div class="prop-section" id="propSecVariable">
                        <label class="prop-label">Variable</label>
                        <select id="propVariable" onchange="updateProp('variable', this.value)" class="prop-input">
                            <option value="">— Seleccionar —</option>
                            @foreach($variables as $k => $l)
                                <option value="{{ $k }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="prop-section hidden" id="propSecImage">
                        <label class="prop-label">Imagen</label>
                        <div id="imagePreviewContainer" class="hidden mb-2">
                            <img id="imagePreview" class="w-full h-24 object-contain border rounded bg-gray-50">
                        </div>
                        <button onclick="replaceImage()" class="prop-btn w-full">Seleccionar imagen</button>
                    </div>
                    <div class="prop-section" id="propSecQR">
                        <p class="text-xs text-gray-500">El código QR se generará automáticamente con el enlace de verificación.</p>
                    </div>
                </div>

                <div class="prop-content hidden" id="tab-style">
                    <div class="prop-section" id="styleSecText">
                        <div class="flex gap-2 mb-2">
                            <div class="flex-1">
                                <label class="prop-label">Fuente</label>
                                <select id="propFontFamily" onchange="updateProp('fontFamily', this.value)" class="prop-input">
                                    <option value="Arial">Arial</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Trebuchet MS">Trebuchet MS</option>
                                    <option value="Impact">Impact</option>
                                </select>
                            </div>
                            <div class="w-20">
                                <label class="prop-label">Tamaño</label>
                                <input type="number" id="propFontSize" value="32" min="1" max="500" oninput="updateProp('fontSize', this.value)" class="prop-input">
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <button onclick="toggleStyle('bold')" id="styleBold" class="style-btn" title="Negrita (Ctrl+B)"><strong>B</strong></button>
                            <button onclick="toggleStyle('italic')" id="styleItalic" class="style-btn" title="Cursiva (Ctrl+I)"><em>I</em></button>
                            <button onclick="toggleStyle('underline')" id="styleUnderline" class="style-btn" title="Subrayado (Ctrl+U)"><u>S</u></button>
                            <div class="w-px h-5 bg-gray-200 mx-1"></div>
                            <input type="color" id="propColor" value="#000000" oninput="updateProp('fill', this.value)" class="color-input" title="Color de texto">
                        </div>
                        <div>
                            <label class="prop-label">Alineación</label>
                            <div class="flex border border-gray-200 rounded overflow-hidden">
                                <button onclick="updateProp('textAlign', 'left')" class="align-btn" data-align="left" title="Izquierda">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'center')" class="align-btn" data-align="center" title="Centro">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 6.75h13.5M9 12h6m-7.5 5.25h9"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'right')" class="align-btn" data-align="right" title="Derecha">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M10.5 12h10.5m-10.5 5.25h16.5"/></svg>
                                </button>
                                <button onclick="updateProp('textAlign', 'justify')" class="align-btn" data-align="justify" title="Justificado">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="prop-section" id="styleSecRect">
                        <div class="flex gap-2 mb-2">
                            <div class="flex-1">
                                <label class="prop-label">Relleno</label>
                                <input type="color" id="propFill" value="#ffffff" oninput="updateProp('fill', this.value)" class="color-input w-full">
                            </div>
                            <div class="flex-1">
                                <label class="prop-label">Borde</label>
                                <input type="color" id="propStroke" value="#000000" oninput="updateProp('stroke', this.value)" class="color-input w-full">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="prop-label">Grosor borde</label>
                                <input type="number" id="propStrokeWidth" value="1" min="0" max="50" oninput="updateProp('strokeWidth', this.value)" class="prop-input">
                            </div>
                            <div class="flex-1">
                                <label class="prop-label">Esquinas</label>
                                <input type="number" id="propRx" value="0" min="0" max="200" oninput="updateProp('rx', this.value)" class="prop-input">
                            </div>
                        </div>
                    </div>
                    <div class="prop-section" id="styleSecLine">
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="prop-label">Color</label>
                                <input type="color" id="propLineColor" value="#000000" oninput="updateProp('stroke', this.value)" class="color-input w-full">
                            </div>
                            <div class="flex-1">
                                <label class="prop-label">Grosor</label>
                                <input type="number" id="propLineWidth" value="2" min="1" max="50" oninput="updateProp('strokeWidth', this.value)" class="prop-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="prop-content hidden" id="tab-advanced">
                    <div class="prop-section">
                        <label class="prop-label">Posición</label>
                        <div class="flex gap-2">
                            <div class="flex-1"><input type="number" id="propX" oninput="updateProp('x', this.value)" class="prop-input" placeholder="X"></div>
                            <div class="flex-1"><input type="number" id="propY" oninput="updateProp('y', this.value)" class="prop-input" placeholder="Y"></div>
                        </div>
                    </div>
                    <div class="prop-section">
                        <label class="prop-label">Tamaño</label>
                        <div class="flex gap-2">
                            <div class="flex-1"><input type="number" id="propW" oninput="updateProp('width', this.value)" class="prop-input" placeholder="Ancho"></div>
                            <div class="flex-1"><input type="number" id="propH" oninput="updateProp('height', this.value)" class="prop-input" placeholder="Alto"></div>
                        </div>
                    </div>
                    <div class="prop-section">
                        <label class="prop-label">Rotación</label>
                        <input type="range" id="propAngle" min="0" max="360" value="0" oninput="updateProp('angle', this.value)" class="w-full">
                        <div class="flex justify-between text-xs text-gray-400"><span>0°</span><span id="angleDisplay">0°</span><span>360°</span></div>
                    </div>
                    <div class="prop-section">
                        <label class="prop-label">Orden (capa)</label>
                        <div class="flex gap-1">
                            <button onclick="moveLayer('up')" class="prop-btn flex-1 text-xs">Subir</button>
                            <button onclick="moveLayer('down')" class="prop-btn flex-1 text-xs">Bajar</button>
                            <button onclick="moveLayer('front')" class="prop-btn flex-1 text-xs">Al frente</button>
                            <button onclick="moveLayer('back')" class="prop-btn flex-1 text-xs">Al fondo</button>
                        </div>
                    </div>
                    <div class="prop-section">
                        <button onclick="duplicateElement()" class="prop-btn mb-2">Duplicar elemento</button>
                        <button onclick="deleteSelected()" class="prop-btn-danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Eliminar elemento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="imageInput" accept="image/*" style="display:none" onchange="handleImageUpload(this)">

    {{-- Context Menu --}}
    <div id="contextMenu" class="context-menu hidden">
        <button onclick="duplicateElement()" class="ctx-item">Duplicar</button>
        <button onclick="moveLayer('front')" class="ctx-item">Traer al frente</button>
        <button onclick="moveLayer('back')" class="ctx-item">Enviar al fondo</button>
        <div class="ctx-divider"></div>
        <button onclick="deleteSelected()" class="ctx-item ctx-danger">Eliminar</button>
    </div>

    {{-- Toast container --}}
    <div id="toastContainer" class="toast-container"></div>

    @push('styles')
    <style>
        * { box-sizing: border-box; }
        .editor-layout { display: flex; height: calc(100vh - 57px); overflow: hidden; font-size: 13px; }

        .header-btn { display: inline-flex; align-items: center; gap: 4px; padding: 5px 8px; border: 1px solid #d0d5dd; border-radius: 6px; background: #fff; color: #344054; font-size: 12px; cursor: pointer; }
        .header-btn:hover { background: #f9fafb; }
        .header-btn:disabled { opacity: .4; cursor: default; }
        .header-btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border: none; border-radius: 6px; background: #2271b1; color: #fff; font-size: 12px; font-weight: 600; cursor: pointer; }
        .header-btn-primary:hover { background: #1a5e9e; }

        .panel-left { width: 220px; min-width: 220px; background: #f6f7f7; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; }
        .panel-tabs { display: flex; border-bottom: 1px solid #e0e0e0; }
        .panel-tab { flex: 1; padding: 9px; border: none; background: none; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #787c82; cursor: pointer; border-bottom: 2px solid transparent; }
        .panel-tab.active { color: #2271b1; border-bottom-color: #2271b1; background: #fff; }
        .panel-body { flex: 1; overflow-y: auto; padding: 8px 0; }

        .element-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
        .element-card { display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 10px 4px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; cursor: grab; font-size: 10.5px; color: #3c434a; transition: all .12s; user-select: none; }
        .element-card:hover { border-color: #2271b1; background: #f0f6fc; color: #2271b1; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,.06); }
        .element-card:active { cursor: grabbing; transform: scale(.97); }
        .element-card svg { width: 18px; height: 18px; }
        .element-card.dragging { opacity: .5; }
        .element-card .drag-hint { font-size: 8px; color: #bbb; margin-top: 1px; }

        .bg-upload-btn { display: flex; align-items: center; gap: 6px; padding: 8px 10px; background: #fff; border: 1px dashed #d0d5dd; border-radius: 6px; cursor: pointer; font-size: 12px; color: #2271b1; transition: all .12s; }
        .bg-upload-btn:hover { border-color: #2271b1; background: #f0f6fc; }
        .bg-upload-btn input { display: none; }

        .var-chip { display: inline-block; padding: 2px 8px; background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 10.5px; cursor: pointer; margin: 2px; transition: all .1s; }
        .var-chip:hover { border-color: #2271b1; background: #f0f6fc; }
        .var-chip code { color: #2271b1; }

        .layer-list { padding: 0 8px; }
        .layer-item { display: flex; align-items: center; gap: 6px; padding: 6px 8px; margin-bottom: 2px; background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; cursor: pointer; font-size: 11px; transition: all .1s; }
        .layer-item:hover { border-color: #2271b1; }
        .layer-item.active { border-color: #2271b1; background: #f0f6fc; }
        .layer-item .layer-icon { width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #787c82; background: #f0f0f1; border-radius: 3px; }
        .layer-item .layer-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .layer-item .layer-vis { width: 16px; height: 16px; cursor: pointer; color: #bbb; }
        .layer-item .layer-vis:hover { color: #3c434a; }
        .layer-item.dragging { opacity: .5; }
        .layer-item.drag-over { border-top: 2px solid #2271b1; }

        .canvas-area { flex: 1; display: flex; flex-direction: column; background: #e2e4e7; overflow: hidden; }
        .canvas-toolbar { display: flex; align-items: center; justify-content: space-between; padding: 4px 12px; background: #fff; border-bottom: 1px solid #e0e0e0; }
        .toolbar-btn { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 26px; border: 1px solid #e0e0e0; border-radius: 4px; background: #fff; cursor: pointer; color: #555; font-size: 13px; }
        .toolbar-btn:hover { background: #f0f0f1; border-color: #c0c0c0; }
        .toolbar-btn.active { background: #2271b1; color: #fff; border-color: #2271b1; }

        .canvas-viewport { flex: 1; overflow: auto; display: flex; align-items: flex-start; justify-content: center; padding: 32px; position: relative; }
        #canvasWrapper { box-shadow: 0 2px 20px rgba(0,0,0,.15); background: #fff; position: relative; transform-origin: top left; transition: transform .05s; }
        #diplomaCanvas { display: block; }
        .canvas-grid #canvasWrapper { background-image: linear-gradient(rgba(0,0,0,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,.05) 1px, transparent 1px); background-size: 20px 20px; }

        .panel-right { width: 270px; min-width: 270px; background: #fff; border-left: 1px solid #e0e0e0; overflow-y: auto; }
        .panel-placeholder { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 24px; }

        .prop-header { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-bottom: 1px solid #f0f0f0; }
        .prop-element-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f0f6fc; color: #2271b1; border-radius: 6px; font-size: 13px; font-weight: 700; }
        .prop-tabs { display: flex; border-bottom: 1px solid #e0e0e0; background: #fafafa; }
        .prop-tab { flex: 1; padding: 8px 4px; border: none; background: none; font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; color: #787c82; cursor: pointer; border-bottom: 2px solid transparent; }
        .prop-tab.active { color: #2271b1; border-bottom-color: #2271b1; background: #fff; }
        .prop-content { display: none; }
        .prop-content.active { display: block; }
        .prop-section { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; }
        .prop-label { display: block; font-size: 10.5px; font-weight: 600; color: #6b7280; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .03em; }
        .prop-input { width: 100%; padding: 6px 8px; font-size: 12px; border: 1px solid #d0d5dd; border-radius: 4px; background: #fff; color: #1d2327; }
        .prop-input:focus { border-color: #2271b1; outline: none; box-shadow: 0 0 0 1px #2271b1; }
        .prop-btn { display: flex; align-items: center; justify-content: center; gap: 4px; padding: 7px 12px; border: 1px solid #d0d5dd; border-radius: 4px; background: #fff; color: #3c434a; font-size: 11px; cursor: pointer; }
        .prop-btn:hover { background: #f9fafb; }
        .prop-btn-danger { display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 8px; border: 1px solid #fecaca; border-radius: 4px; background: #fef2f2; color: #dc2626; font-size: 11px; font-weight: 600; cursor: pointer; }
        .prop-btn-danger:hover { background: #fee2e2; }

        .style-btn { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid #d0d5dd; border-radius: 4px; background: #fff; cursor: pointer; color: #3c434a; font-size: 12px; }
        .style-btn:hover { background: #f0f0f1; }
        .style-btn.active { background: #2271b1; color: #fff; border-color: #2271b1; }
        .style-btn strong { font-weight: 700; }
        .style-btn em { font-style: italic; }
        .style-btn u { text-decoration: underline; }
        .color-input { height: 30px; border: 1px solid #d0d5dd; border-radius: 4px; padding: 2px; cursor: pointer; }
        .align-btn { flex: 1; display: flex; align-items: center; justify-content: center; padding: 6px; border: none; background: #fff; cursor: pointer; color: #6b7280; }
        .align-btn:not(:last-child) { border-right: 1px solid #e0e0e0; }
        .align-btn:hover { background: #f0f6fc; }
        .align-btn.active { background: #2271b1; color: #fff; }

        .context-menu { position: fixed; z-index: 9999; min-width: 160px; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.12); padding: 4px; }
        .ctx-item { display: block; width: 100%; padding: 7px 12px; border: none; background: none; text-align: left; font-size: 12px; color: #3c434a; cursor: pointer; border-radius: 4px; }
        .ctx-item:hover { background: #f0f6fc; }
        .ctx-danger { color: #dc2626; }
        .ctx-danger:hover { background: #fef2f2; }
        .ctx-divider { height: 1px; background: #e0e0e0; margin: 4px 8px; }

        .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 8px; }
        .toast { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,.1); animation: toastIn .25s ease; max-width: 320px; }
        .toast-success { background: #065f46; color: #fff; }
        .toast-error { background: #991b1b; color: #fff; }
        .toast-info { background: #1e40af; color: #fff; }
        @keyframes toastIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes toastOut { from { opacity: 1; } to { opacity: 0; transform: translateY(10px); } }

        .hidden { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        // ─── Canvas Setup ───
        const c = new fabric.Canvas('diplomaCanvas', { preserveObjectStacking: true, selection: true });
        const W = {{ $template->canvas_width }}, H = {{ $template->canvas_height }};
        const existingElements = @json($template->elements);
        const variables = @json($variables);
        let elIdCounter = 0;
        let zoom = 1;
        let showGrid = false;
        let currentTab = 'content';

        // ─── Undo/Redo ───
        let history = [];
        let historyIndex = -1;
        const MAX_HISTORY = 50;

        function saveState() {
            const json = JSON.stringify(c.toJSON(['_elId','_tipo','_variable','_src']));
            if (historyIndex < history.length - 1) history = history.slice(0, historyIndex + 1);
            history.push(json);
            if (history.length > MAX_HISTORY) history.shift();
            historyIndex = history.length - 1;
            updateUndoButtons();
        }

        function undo() {
            if (historyIndex <= 0) return;
            historyIndex--;
            loadState(history[historyIndex]);
            updateUndoButtons();
            toast('Deshecho', 'info');
        }

        function redo() {
            if (historyIndex >= history.length - 1) return;
            historyIndex++;
            loadState(history[historyIndex]);
            updateUndoButtons();
            toast('Rehecho', 'info');
        }

        function loadState(json) {
            c.loadFromJSON(JSON.parse(json), function() {
                c.renderAll();
                refreshLayers();
            });
        }

        function updateUndoButtons() {
            document.getElementById('undoBtn').disabled = historyIndex <= 0;
            document.getElementById('redoBtn').disabled = historyIndex >= history.length - 1;
        }

        function autoSave() { saveState(); }

        // ─── Background ───
        @if($template->background_image)
        fabric.Image.fromURL('{{ Storage::url($template->background_image) }}', function(img) {
            c.setBackgroundImage(img, c.renderAll.bind(c), { scaleX: W / img.width, scaleY: H / img.height });
            saveState();
        });
        @else
        saveState();
        @endif

        // ─── Load Elements ───
        existingElements.forEach(el => createEl(el));
        refreshLayers();

        function createEl(el) {
            const config = el.config_json || {};
            const id = el.id || 'el_' + (++elIdCounter);
            let obj;
            switch (el.tipo) {
                case 'text':
                    obj = new fabric.Textbox(config.text || 'Texto', { left: el.x, top: el.y, width: el.width, height: el.height, fontSize: config.fontSize || 32, fill: config.fill || '#000000', textAlign: config.textAlign || 'left', fontFamily: config.fontFamily || 'Arial', fontStyle: config.italic ? 'italic' : 'normal', fontWeight: config.bold ? 'bold' : 'normal', underline: config.underline || false, splitByGrapheme: true }); break;
                case 'variable':
                    obj = new fabric.Textbox('@{{' + (el.variable||'variable') + '}}', { left: el.x, top: el.y, width: el.width, height: el.height, fontSize: config.fontSize || 32, fill: config.fill || '#000000', textAlign: config.textAlign || 'left', fontFamily: config.fontFamily || 'Arial', fontStyle: config.italic ? 'italic' : 'normal', fontWeight: config.bold ? 'bold' : 'normal', underline: config.underline || false, splitByGrapheme: true }); obj._variable = el.variable; break;
                case 'qr':
                    obj = new fabric.Rect({ left: el.x, top: el.y, width: el.width||120, height: el.height||120, fill: '#f8f8f8', stroke: '#999', strokeWidth: 1, strokeDashArray: [5, 3], rx: 2, ry: 2 }); break;
                case 'rect':
                    obj = new fabric.Rect({ left: el.x, top: el.y, width: el.width, height: el.height, fill: config.fill||'transparent', stroke: config.stroke||'#000', strokeWidth: config.strokeWidth||1, rx: config.rx||0, ry: config.ry||0 }); break;
                case 'line':
                    obj = new fabric.Line([el.x,el.y,el.x+el.width,el.y+el.height], { stroke: config.stroke||'#000', strokeWidth: config.strokeWidth||2 }); break;
                case 'image':
                    if (config.src) { fabric.Image.fromURL(config.src, function(img){ img.set({left:el.x,top:el.y,scaleX:el.width/img.width,scaleY:el.height/img.height}); img._elId=id; img._tipo='image'; img._src=config.src; c.add(img);c.renderAll();refreshLayers(); }); return; }
            }
            if (obj) { obj._elId = id; obj._tipo = el.tipo; obj._variable = el.variable||null; c.add(obj); c.renderAll(); }
        }

        function uid() { return 'el_' + (++elIdCounter); }

        function makeObj(tipo, opts = {}) {
            let obj;
            const x = opts.x||100, y = opts.y||100;
            switch (tipo) {
                case 'text': obj = new fabric.Textbox(opts.text||'Texto', { left:x, top:y, width:300, height:60, fontSize:32, fill:'#000', splitByGrapheme:true }); break;
                case 'variable': obj = new fabric.Textbox('@{{'+(opts.key||'full_name')+'}}', { left:x, top:y, width:300, height:60, fontSize:32, fill:'#000', splitByGrapheme:true }); obj._variable = opts.key||'full_name'; break;
                case 'qr': obj = new fabric.Rect({ left:x, top:y, width:120, height:120, fill:'#f8f8f8', stroke:'#999', strokeWidth:1, strokeDashArray:[5,3], rx:2, ry:2 }); break;
                case 'rect': obj = new fabric.Rect({ left:x, top:y, width:200, height:100, fill:'transparent', stroke:'#000', strokeWidth:1 }); break;
                case 'line': obj = new fabric.Line([x,y,x+200,y], { stroke:'#000', strokeWidth:2 }); break;
                case 'image': document.getElementById('imageInput').click(); return null;
            }
            if (obj) { obj._elId = uid(); obj._tipo = tipo; c.add(obj); c.setActiveObject(obj); c.renderAll(); autoSave(); refreshLayers(); }
            return obj;
        }

        function addText() { makeObj('text'); toast('Texto añadido', 'success'); }
        function addVariable() { makeObj('variable', {key: Object.keys(variables)[0]}); toast('Variable añadida', 'success'); }
        function addQr() { makeObj('qr'); toast('QR añadido', 'success'); }
        function addRect() { makeObj('rect'); toast('Figura añadida', 'success'); }
        function addLine() { makeObj('line'); toast('Línea añadida', 'success'); }
        function addImage() { document.getElementById('imageInput').click(); }

        function handleImageUpload(input) {
            const file = input.files[0]; if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                fabric.Image.fromURL(e.target.result, function(img) {
                    img.set({ left: 100, top: 100, scaleX: 0.5, scaleY: 0.5 });
                    img._elId = uid(); img._tipo = 'image'; img._src = e.target.result;
                    c.add(img); c.setActiveObject(img); c.renderAll(); autoSave(); refreshLayers();
                    toast('Imagen añadida', 'success');
                });
            }; reader.readAsDataURL(file); input.value = '';
        }

        // ─── Drag from sidebar ───
        function onDragStart(e, tipo) {
            e.dataTransfer.setData('text/plain', tipo);
            e.dataTransfer.effectAllowed = 'copy';
            e.target.classList.add('dragging');
            setTimeout(() => e.target.classList.remove('dragging'), 0);
        }

        document.getElementById('canvasViewport').addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
        });

        document.getElementById('canvasViewport').addEventListener('drop', function(e) {
            e.preventDefault();
            const tipo = e.dataTransfer.getData('text/plain');
            if (!tipo) return;
            const rect = c.getElement().getBoundingClientRect();
            const scale = rect.width / W;
            const x = (e.clientX - rect.left) / scale;
            const y = (e.clientY - rect.top) / scale;
            if (tipo === 'image') {
                document.getElementById('imageInput').click();
                return;
            }
            if (tipo === 'variable') {
                const obj = makeObj('variable', { x, y, key: Object.keys(variables)[0] });
            } else {
                const opts = { x, y };
                if (tipo === 'text') opts.text = 'Texto';
                makeObj(tipo, opts);
            }
            toast('Elemento agregado desde arrastre', 'success');
        });

        // ─── Properties ───
        c.on('selection:created', e => showProps(e.selected[0]));
        c.on('selection:updated', e => showProps(e.selected[0]));
        c.on('selection:cleared', () => hideProps());
        c.on('object:moving', e => { if (currentElId) updateInputs(e.target); });
        c.on('object:scaling', e => { if (currentElId) updateInputs(e.target); });
        c.on('object:modified', () => { if (currentElId) updateInputs(c.getActiveObject()); autoSave(); refreshLayers(); });

        let currentElId = null;

        function showProps(obj) {
            currentElId = obj._elId;
            document.getElementById('panelPlaceholder').classList.add('hidden');
            document.getElementById('panelContent').classList.remove('hidden');
            document.getElementById('selectionInfo').textContent = `X:${Math.round(obj.left)} Y:${Math.round(obj.top)} W:${Math.round(obj.width)} H:${Math.round(obj.height)}`;

            const tipo = obj._tipo||'';
            const names = { text:'Texto', variable:'Variable', qr:'Código QR', rect:'Figura', line:'Línea', image:'Imagen' };
            document.getElementById('propType').textContent = names[tipo]||tipo;
            document.getElementById('propSubtype').textContent = tipo==='variable' ? (obj._variable||'') : (tipo==='image'?'Imagen insertada':'Elemento visual');
            const iconMap = { text:'T', variable:'{x}', qr:[], rect:[], line:'—', image:[] };
            const icons = { text:'<strong style="font-size:16px">T</strong>', variable:'<span style="font-size:14px;font-weight:700">{···}</span>', qr:'<span style="font-size:14px;font-weight:700">▦</span>', rect:'<span style="font-size:16px">■</span>', line:'<span style="font-size:16px">╌</span>', image:'<span style="font-size:16px">🖼</span>' };
            document.getElementById('propIcon').innerHTML = icons[tipo]||'•';

            document.getElementById('propSecText').style.display = (tipo==='text'||tipo==='variable')?'':'none';
            document.getElementById('propSecVariable').style.display = tipo==='variable'?'':'none';
            document.getElementById('propSecImage').style.display = tipo==='image'?'':'none';
            document.getElementById('propSecQR').style.display = tipo==='qr'?'':'none';
            document.getElementById('styleSecText').style.display = (tipo==='text'||tipo==='variable')?'':'none';
            document.getElementById('styleSecRect').style.display = tipo==='rect'?'':'none';
            document.getElementById('styleSecLine').style.display = tipo==='line'?'':'none';

            if (tipo==='image' && obj._src) {
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                document.getElementById('imagePreview').src = obj._src;
            } else {
                document.getElementById('imagePreviewContainer').classList.add('hidden');
            }

            updateInputs(obj);
        }

        function hideProps() {
            currentElId = null;
            document.getElementById('panelPlaceholder').classList.remove('hidden');
            document.getElementById('panelContent').classList.add('hidden');
            document.getElementById('selectionInfo').textContent = 'Selecciona un elemento';
        }

        function updateInputs(obj) {
            if (!obj) return;
            document.getElementById('propX').value = Math.round(obj.left);
            document.getElementById('propY').value = Math.round(obj.top);
            document.getElementById('propW').value = Math.round(obj.width * (obj.scaleX||1));
            document.getElementById('propH').value = Math.round(obj.height * (obj.scaleY||1));
            document.getElementById('propAngle').value = Math.round(obj.angle||0);
            document.getElementById('angleDisplay').textContent = Math.round(obj.angle||0) + '°';

            if (obj._tipo==='text'||obj._tipo==='variable') {
                document.getElementById('propText').value = obj.text||'';
                document.getElementById('propFontSize').value = obj.fontSize||32;
                document.getElementById('propFontFamily').value = obj.fontFamily||'Arial';
                document.getElementById('propColor').value = obj.fill||'#000000';
                document.getElementById('styleBold').classList.toggle('active', obj.fontWeight==='bold');
                document.getElementById('styleItalic').classList.toggle('active', obj.fontStyle==='italic');
                document.getElementById('styleUnderline').classList.toggle('active', !!obj.underline);
                document.querySelectorAll('.align-btn').forEach(b => b.classList.toggle('active', b.dataset.align===(obj.textAlign||'left')));
                if (obj._tipo==='variable') document.getElementById('propVariable').value = obj._variable||'';
            }
            if (obj._tipo==='rect') {
                document.getElementById('propFill').value = obj.fill||'#ffffff';
                document.getElementById('propStroke').value = obj.stroke||'#000000';
                document.getElementById('propStrokeWidth').value = obj.strokeWidth||1;
                document.getElementById('propRx').value = obj.rx||0;
            }
            if (obj._tipo==='line') {
                document.getElementById('propLineColor').value = obj.stroke||'#000000';
                document.getElementById('propLineWidth').value = obj.strokeWidth||2;
            }
        }

        function updateProp(prop, value) {
            const obj = c.getActiveObject(); if (!obj) return;
            const m = { text:'text', variable:'variable', x:'left', y:'top', width:'width', height:'height', fontSize:'fontSize', fontFamily:'fontFamily', fill:'fill', stroke:'stroke', strokeWidth:'strokeWidth', rx:'rx', textAlign:'textAlign', angle:'angle' };
            if (prop === 'variable') { obj._variable = value; obj.set('text', '@{{'+value+'}}'); }
            else if (prop === 'bold' || prop === 'italic' || prop === 'underline') toggleStyle(prop);
            else if (prop === 'textAlign') { obj.set('textAlign', value); document.querySelectorAll('.align-btn').forEach(b => b.classList.toggle('active', b.dataset.align===value)); }
            else if (m[prop]) obj.set(m[prop], parseFloat(value) || value);
            c.renderAll(); if (currentElId) updateInputs(obj);
        }

        function toggleStyle(s) {
            const obj = c.getActiveObject(); if (!obj) return;
            const btn = { bold:'styleBold', italic:'styleItalic', underline:'styleUnderline' }[s];
            const el = document.getElementById(btn);
            if (s==='bold') { const v=obj.fontWeight==='bold'?'normal':'bold'; obj.set('fontWeight',v); el.classList.toggle('active'); }
            else if (s==='italic') { const v=obj.fontStyle==='italic'?'normal':'italic'; obj.set('fontStyle',v); el.classList.toggle('active'); }
            else if (s==='underline') { const v=!obj.underline; obj.set('underline',v); el.classList.toggle('active'); }
            c.renderAll();
        }

        function switchTab(tab, btn) {
            currentTab = tab;
            document.querySelectorAll('.prop-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.prop-content').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-'+tab).classList.add('active');
        }

        function switchPanel(panel) {
            document.querySelectorAll('.panel-tab').forEach(t => t.classList.toggle('active', t.dataset.panel===panel));
            document.querySelectorAll('.panel-body').forEach(p => p.classList.toggle('hidden', p.id!=='panel-'+panel));
        }

        function deleteSelected() {
            const obj = c.getActiveObject(); if (!obj) return;
            c.remove(obj); c.discardActiveObject(); hideProps(); c.renderAll(); autoSave(); refreshLayers();
            toast('Elemento eliminado', 'info');
        }

        function duplicateElement() {
            const obj = c.getActiveObject(); if (!obj) return;
            obj.clone(function(clone) {
                clone.set({ left: (clone.left||0)+20, top: (clone.top||0)+20 });
                clone._elId = uid();
                clone._tipo = obj._tipo;
                clone._variable = obj._variable;
                clone._src = obj._src;
                c.add(clone); c.setActiveObject(clone); c.renderAll(); autoSave(); refreshLayers();
                toast('Elemento duplicado', 'success');
            });
        }

        function moveLayer(dir) {
            const obj = c.getActiveObject(); if (!obj) return;
            if (dir==='up') c.bringForward(obj);
            else if (dir==='down') c.sendBackwards(obj);
            else if (dir==='front') c.bringToFront(obj);
            else if (dir==='back') c.sendToBack(obj);
            c.renderAll(); autoSave(); refreshLayers();
        }

        function replaceImage() { document.getElementById('imageInput').click(); }

        function insertVariable(key) {
            const obj = c.getActiveObject();
            if (obj && (obj._tipo==='text'||obj._tipo==='variable')) {
                obj.set('text', (obj.text||'') + '@{{'+key+'}}'); c.renderAll(); autoSave();
                toast('Variable '+key+' insertada', 'success');
            } else toast('Selecciona un texto primero', 'info');
        }

        // ─── Layers ───
        function refreshLayers() {
            const list = document.getElementById('layerList');
            const empty = document.getElementById('layerEmpty');
            const objs = c.getObjects();
            if (!objs.length) { list.innerHTML = ''; empty.classList.remove('hidden'); return; }
            empty.classList.add('hidden');
            const names = { text:'Texto', variable:'Variable', qr:'QR', rect:'Figura', line:'Línea', image:'Imagen' };
            const icons = { text:'T', variable:'{x}', qr:'▦', rect:'■', line:'—', image:'🖼' };
            const sel = c.getActiveObject();
            list.innerHTML = objs.slice().reverse().map((o,i) => {
                const t = o._tipo||'text';
                const active = sel && sel._elId === o._elId ? 'active' : '';
                return `<div class="layer-item ${active}" onclick="selectLayer('${o._elId}')" draggable="false">
                    <span class="layer-icon">${icons[t]||'•'}</span>
                    <span class="layer-name">${names[t]||t}${t==='variable'&&o._variable?' ('+o._variable+')':''}</span>
                    <span class="layer-vis" onclick="event.stopPropagation();c.remove(o);c.renderAll();autoSave();refreshLayers();hideProps();" title="Eliminar">✕</span>
                </div>`;
            }).join('');
        }

        function selectLayer(id) {
            const objs = c.getObjects().filter(o => o._elId === id);
            if (objs.length) { c.setActiveObject(objs[0]); c.renderAll(); refreshLayers(); }
        }

        // ─── Zoom ───
        function applyZoom() {
            const wrapper = document.getElementById('canvasWrapper');
            wrapper.style.transform = `scale(${zoom})`;
            document.getElementById('zoomDisplay').textContent = Math.round(zoom*100)+'%';
        }
        function zoomIn() { zoom = Math.min(zoom+0.1, 3); applyZoom(); }
        function zoomOut() { zoom = Math.max(zoom-0.1, 0.2); applyZoom(); }
        function zoomFit() {
            const vp = document.getElementById('canvasViewport');
            const sx = (vp.clientWidth-64)/W, sy = (vp.clientHeight-64)/H;
            zoom = Math.min(sx, sy, 2);
            applyZoom();
        }

        // ─── Grid ───
        function toggleGrid() {
            showGrid = !showGrid;
            document.getElementById('canvasViewport').classList.toggle('canvas-grid', showGrid);
            document.getElementById('gridBtn').classList.toggle('active', showGrid);
        }

        // ─── Context Menu ───
        c.on('mouse:down', function(e) { if (e.button !== 3) hideContextMenu(); });

        c.on('mouse:down', function(o) {
            if (o.button === 3) {
                const ev = o.e;
                ev.preventDefault();
                const target = c.findTarget(ev, false);
                if (target) { c.setActiveObject(target); c.renderAll(); showContextMenu(ev.clientX, ev.clientY); }
                else hideContextMenu();
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.context-menu')) hideContextMenu();
        });
        document.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.editor-layout')) e.preventDefault();
        });

        function showContextMenu(x, y) {
            const menu = document.getElementById('contextMenu');
            menu.classList.remove('hidden'); menu.style.left = menu.style.top = '0px';
            const r = menu.getBoundingClientRect(), vw = window.innerWidth, vh = window.innerHeight;
            const left = Math.min(x, vw - r.width - 8), top = Math.min(y, vh - r.height - 8);
            menu.style.left = Math.max(8, left) + 'px'; menu.style.top = Math.max(8, top) + 'px';
        }
        function hideContextMenu() { document.getElementById('contextMenu').classList.add('hidden'); }

        // ─── Toast ───
        function toast(msg, type='info') {
            const container = document.getElementById('toastContainer');
            const el = document.createElement('div');
            el.className = 'toast toast-'+type;
            el.textContent = msg;
            container.appendChild(el);
            setTimeout(() => { el.style.animation = 'toastOut .2s ease forwards'; setTimeout(() => el.remove(), 200); }, 2500);
        }

        // ─── Keyboard shortcuts ───
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
            if (e.ctrlKey && e.key === 'z' && !e.shiftKey) { e.preventDefault(); undo(); }
            else if (e.ctrlKey && e.key === 'z' && e.shiftKey) { e.preventDefault(); redo(); }
            else if (e.ctrlKey && e.key === 'y') { e.preventDefault(); redo(); }
            else if (e.key === 'Delete' || e.key === 'Backspace') { deleteSelected(); }
            else if (e.ctrlKey && e.key === 'd') { e.preventDefault(); duplicateElement(); }
            else if (e.key === 'ArrowUp') { const o=c.getActiveObject(), n=e.shiftKey?10:1; if(o){o.set('top',(o.top||0)-n);c.renderAll();updateInputs(o);} }
            else if (e.key === 'ArrowDown') { const o=c.getActiveObject(), n=e.shiftKey?10:1; if(o){o.set('top',(o.top||0)+n);c.renderAll();updateInputs(o);} }
            else if (e.key === 'ArrowLeft') { const o=c.getActiveObject(), n=e.shiftKey?10:1; if(o){o.set('left',(o.left||0)-n);c.renderAll();updateInputs(o);} }
            else if (e.key === 'ArrowRight') { const o=c.getActiveObject(), n=e.shiftKey?10:1; if(o){o.set('left',(o.left||0)+n);c.renderAll();updateInputs(o);} }
            else if (e.ctrlKey && e.key === 's') { e.preventDefault(); saveCanvas(); }
        });

        // ─── Background ───
        function uploadBackground(input) {
            const file = input.files[0]; if (!file) return;
            const fd = new FormData(); fd.append('image', file); fd.append('_token','{{ csrf_token() }}');
            fetch('{{ route('admin.templates.upload-background', $template) }}', { method:'POST', body:fd })
            .then(r=>r.json()).then(d=>{ if(d.url){ fabric.Image.fromURL(d.url,function(img){ c.setBackgroundImage(img,c.renderAll.bind(c),{scaleX:W/img.width,scaleY:H/img.height}); autoSave(); toast('Fondo actualizado','success'); }); }});
        }
        function removeBackground() {
            fetch('{{ route('admin.templates.remove-background', $template) }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'} })
            .then(()=>{ c.setBackgroundImage(null, c.renderAll.bind(c)); autoSave(); toast('Fondo eliminado','info'); });
        }

        // ─── Save ───
        function getElements() {
            return c.getObjects().map(function(o,i) {
                if (o._tipo === 'image' && o._src && o._src.startsWith('data:')) return null;
                let cfg = {};
                if (o._tipo==='text'||o._tipo==='variable') cfg = { text:o.text, fontSize:o.fontSize, fill:o.fill, fontFamily:o.fontFamily, textAlign:o.textAlign, bold:o.fontWeight==='bold', italic:o.fontStyle==='italic', underline:o.underline };
                else if (o._tipo==='rect') cfg = { fill:o.fill, stroke:o.stroke, strokeWidth:o.strokeWidth, rx:o.rx, ry:o.ry };
                else if (o._tipo==='line') cfg = { stroke:o.stroke, strokeWidth:o.strokeWidth };
                else if (o._tipo==='image') cfg = { src:o._src||'' };
                return { id:o._elId&&!o._elId.toString().startsWith('el_')?parseInt(o._elId):null, tipo:o._tipo||'text', variable:o._variable||null, x:Math.round(o.left), y:Math.round(o.top), width:Math.round(o.width*(o.scaleX||1)), height:Math.round(o.height*(o.scaleY||1)), config_json:JSON.stringify(cfg), orden:i+1 };
            }).filter(Boolean);
        }

        function saveCanvas() {
            const elements = getElements();
            const btn = document.getElementById('saveBtn');
            btn.innerHTML = 'Guardando...'; btn.disabled = true;
            fetch('{{ route('admin.templates.save-elements', $template) }}', {
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify({ elements })
            })
            .then(r=>r.json()).then(d=>{
                if (d.success) { toast('Diseño guardado correctamente','success'); btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardado'; }
                else { toast('Error al guardar: '+(d.message||'desconocido'),'error'); btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardar'; }
                setTimeout(() => { btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardar'; btn.disabled = false; }, 2000);
            }).catch(e => {
                toast('Error de red: '+e.message,'error');
                btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Guardar';
                btn.disabled = false;
            });
        }

        function previewCanvas() {
            const dataUrl = c.toDataURL({ format:'png', multiplier:2 });
            const w = window.open('','_blank');
            w.document.write(`<html><head><title>Vista previa</title><style>body{margin:0;display:flex;justify-content:center;align-items:center;min-height:100vh;background:#f3f4f6;}img{max-width:100%;box-shadow:0 4px 20px rgba(0,0,0,.2);border-radius:4px;}</style></head><body><img src="${dataUrl}" /></body></html>`);
        }

        // ─── Init ───
        setTimeout(zoomFit, 100);
        window.addEventListener('resize', zoomFit);
    </script>
    @endpush
</x-app-layout>
