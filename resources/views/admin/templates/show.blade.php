<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $template->nombre }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">Curso</dt>
                    <dd class="font-medium">{{ $template->curso->nombre }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Tamaño</dt>
                    <dd class="font-medium">{{ $template->canvas_width }} × {{ $template->canvas_height }} px</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Fondo</dt>
                    <dd class="font-medium">
                        @if($template->background_image)
                            <span class="text-green-600">✔ Subido</span>
                        @else
                            <span class="text-gray-400">Sin fondo</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Elementos</dt>
                    <dd class="font-medium">{{ $template->elements->count() }}</dd>
                </div>
            </dl>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.templates.editor', $template) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Diseñar</a>
                <a href="{{ route('admin.templates.edit', $template) }}"
                   class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Editar</a>
            </div>
        </div>

        @if($template->background_image)
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="font-medium mb-2">Vista previa del fondo</h3>
                <img src="{{ Storage::url($template->background_image) }}"
                     class="max-w-full h-auto rounded border">
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="font-medium mb-3">Elementos ({{ $template->elements->count() }})</h3>
            @if($template->elements->isEmpty())
                <p class="text-gray-400 text-sm">Aún no hay elementos. Usa el editor para agregarlos.</p>
            @else
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-2 text-left">#</th>
                            <th class="px-3 py-2 text-left">Tipo</th>
                            <th class="px-3 py-2 text-left">Variable</th>
                            <th class="px-3 py-2 text-left">Posición</th>
                            <th class="px-3 py-2 text-left">Tamaño</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($template->elements as $el)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-3 py-2">{{ $el->orden }}</td>
                                <td class="px-3 py-2">{{ $el->tipo }}</td>
                                <td class="px-3 py-2 text-gray-500">{{ $el->variable ?? '—' }}</td>
                                <td class="px-3 py-2">{{ round($el->x) }}, {{ round($el->y) }}</td>
                                <td class="px-3 py-2">{{ round($el->width) }} × {{ round($el->height) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
