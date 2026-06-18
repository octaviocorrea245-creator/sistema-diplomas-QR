<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nueva Plantilla de Diploma</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <form method="POST" action="{{ route('admin.templates.store') }}" class="bg-white shadow rounded-lg p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Curso</label>
                <select name="curso_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Seleccionar curso...</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" @selected(old('curso_id') == $curso->id)>
                            {{ $curso->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('curso_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre de la plantilla</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="255"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ancho (px)</label>
                    <input type="number" name="canvas_width" value="{{ old('canvas_width', 1920) }}" required min="100" max="4000"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alto (px)</label>
                    <input type="number" name="canvas_height" value="{{ old('canvas_height', 1358) }}" required min="100" max="4000"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>
            <p class="text-xs text-gray-400">Relación 1920×1358 ≈ tamaño carta horizontal.</p>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.templates.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Crear</button>
            </div>
        </form>
    </div>
</x-app-layout>
