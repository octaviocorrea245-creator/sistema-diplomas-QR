<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Plantillas de Diploma</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Lista de Plantillas</h3>
            <a href="{{ route('admin.templates.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nueva Plantilla
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($templates as $template)
                <div class="bg-white shadow rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                    @if($template->background_image)
                        <img src="{{ Storage::url($template->background_image) }}"
                             class="w-full h-32 object-cover rounded mb-3 bg-gray-100">
                    @else
                        <div class="w-full h-32 bg-gray-100 rounded mb-3 flex items-center justify-center text-gray-400 text-sm">
                            Sin fondo
                        </div>
                    @endif

                    <h4 class="font-semibold text-gray-800">{{ $template->nombre }}</h4>
                    <p class="text-sm text-gray-500">
                        Curso: {{ $template->curso->nombre ?? '—' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $template->canvas_width }} × {{ $template->canvas_height }} px</p>

                    <div class="flex gap-3 mt-3 text-sm">
                        <a href="{{ route('admin.templates.editor', $template) }}"
                           class="text-blue-600 hover:underline">Diseñar</a>
                        <a href="{{ route('admin.templates.show', $template) }}"
                           class="text-gray-600 hover:underline">Ver</a>
                        <a href="{{ route('admin.templates.edit', $template) }}"
                           class="text-yellow-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.templates.destroy', $template) }}"
                              method="POST" onsubmit="return confirm('¿Eliminar esta plantilla?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    No hay plantillas aún.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
