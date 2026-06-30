<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Vista Previa — {{ $template->nombre }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.templates.editor', $template) }}"
                   class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Diseñar</a>
                <a href="{{ route('admin.templates.show', $template) }}"
                   class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Detalles</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 mb-3 flex items-center gap-4">
            <p class="text-sm text-gray-500">
                Vista previa con datos de ejemplo.
            </p>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                <span><strong class="text-gray-600">Tamaño:</strong> {{ $template->canvas_width }} × {{ $template->canvas_height }} px</span>
                <span><strong class="text-gray-600">Curso:</strong> {{ $template->curso->nombre }}</span>
                <span><strong class="text-gray-600">Elementos:</strong> {{ $template->elements->count() }}</span>
            </div>
        </div>

        <div id="preview-container"
             class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
             style="height: calc(100vh - 200px); position:relative;">
            <div id="diploma-content" style="position:absolute;top:50%;left:50%;">
                {!! $diplomaHtml !!}
            </div>
        </div>
    </div>

    <script>
        (function() {
            var container = document.getElementById('preview-container');
            var diploma = document.getElementById('diploma-content');
            function scaleDiploma() {
                var cw = container.clientWidth - 48;
                var ch = container.clientHeight - 48;
                var dw = {{ $template->canvas_width }};
                var dh = {{ $template->canvas_height }};
                var scale = Math.min(cw / dw, ch / dh, 1);
                diploma.style.transform = 'translate(-50%,-50%) scale(' + scale + ')';
            }
            scaleDiploma();
            window.addEventListener('resize', scaleDiploma);
        })();
    </script>
</x-app-layout>