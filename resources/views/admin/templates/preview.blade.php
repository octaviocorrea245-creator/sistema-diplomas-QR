<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vista previa: {{ $template->nombre }}
        </h2>
    </x-slot>

    <div style="padding:1.5rem;max-width:100%;display:flex;flex-direction:column;align-items:center;">
        <div style="margin-bottom:1rem;display:flex;gap:0.75rem;align-items:center;">
            <a href="{{ route('admin.templates.editor', $template) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                Editar plantilla
            </a>
            <a href="{{ route('admin.cursos.show', $template->curso) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-sm">
                Volver al curso
            </a>
        </div>

        <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);background:#fff;">
            <div style="position:relative;width:{{ $template->canvas_width }}px;height:{{ $template->canvas_height }}px;overflow:hidden;
                {{ $template->background_image ? "background:url('" . asset($template->background_image) . "') no-repeat center/cover;" : 'background:#f8fafc;' }}">
                @foreach($template->elements->sortBy('orden') as $el)
                    @php
                        $cfg = is_string($el->config_json) ? json_decode($el->config_json, true) : ($el->config_json ?? []);
                        $fs = $cfg['fontSize'] ?? 24;
                        $color = $cfg['fill'] ?? '#000000';
                        $align = $cfg['textAlign'] ?? 'left';
                        $bold = !empty($cfg['bold']) ? 'bold' : 'normal';
                        $italic = !empty($cfg['italic']) ? 'italic' : 'normal';
                    @endphp
                    <div style="position:absolute;left:{{ $el->x }}px;top:{{ $el->y }}px;width:{{ $el->width }}px;height:{{ $el->height }}px;overflow:hidden;
                        @if($el->tipo === 'text' || $el->tipo === 'variable')
                            font-size:{{ $fs }}px;color:{{ $color }};text-align:{{ $align }};font-weight:{{ $bold }};font-style:{{ $italic }};
                            display:flex;align-items:center;
                        @endif
                    ">
                        @switch($el->tipo)
                            @case('text')
                                {{ $cfg['text'] ?? '' }}
                                @break
                            @case('variable')
                                {{ $sampleData[$el->variable] ?? $el->variable }}
                                @break
                            @case('qr')
                                <img src="{{ $qrBase64 }}" style="width:100%;height:100%;object-fit:contain;">
                                @break
                            @case('rect')
                                <div style="width:100%;height:100%;
                                    background:{{ $cfg['fill'] ?? 'transparent' }};
                                    border:{{ $cfg['strokeWidth'] ?? 1 }}px solid {{ $cfg['stroke'] ?? '#000' }};
                                    border-radius:{{ $cfg['rx'] ?? 0 }}px;"></div>
                                @break
                            @case('line')
                                <svg width="{{ $el->width }}" height="{{ $el->height }}" style="overflow:visible;">
                                    <line x1="0" y1="0" x2="{{ $el->width }}" y2="{{ $el->height }}"
                                        stroke="{{ $cfg['stroke'] ?? '#000' }}" stroke-width="{{ $cfg['strokeWidth'] ?? 2 }}" />
                                </svg>
                                @break
                            @case('image')
                                @if(!empty($cfg['path']))
                                    <img src="{{ asset($cfg['path']) }}" style="width:100%;height:100%;object-fit:contain;">
                                @endif
                                @break
                            @case('firma')
                                <div style="width:100%;height:100%;display:flex;flex-direction:column;justify-content:flex-end;padding:4px;">
                                    @if(!empty($cfg['mostrar_cargo']) && !empty($cfg['mostrar_nombre']))
                                    <div style="border-top:2px solid {{ $color }};margin-bottom:4px;"></div>
                                    <div style="font-size:{{ max(8, $fs) }}px;font-weight:bold;color:{{ $color }};text-align:center;">
                                        {{ $cfg['firma_nombre'] ?? 'Nombre del Firmante' }}
                                    </div>
                                    <div style="font-size:{{ max(7, $fs * 0.85) }}px;color:{{ $color }};text-align:center;">
                                        {{ $cfg['firma_cargo'] ?? 'Cargo del Firmante' }}
                                    </div>
                                    @endif
                                </div>
                                @break
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top:1rem;font-size:0.8rem;color:#6b7280;text-align:center;">
            Canvas: {{ $template->canvas_width }} × {{ $template->canvas_height }} px &middot;
            {{ $template->elements->count() }} elemento(s) &middot;
            Vista previa con datos de ejemplo
        </div>
    </div>
</x-app-layout>