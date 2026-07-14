<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Plantilla</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 rounded p-3 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.plantillas.update', $plantilla) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre', $plantilla->nombre) }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $plantilla->descripcion) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (['global', 'grupo', 'individual'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $plantilla->tipo) === $tipo ? 'selected' : '' }}>
                            {{ ucfirst($tipo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Asignar a cursos</label>
                <div class="border rounded p-3 max-h-48 overflow-y-auto space-y-1">
                    @forelse($cursos as $curso)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="cursos[]" value="{{ $curso->id }}"
                                {{ in_array($curso->id, old('cursos', $plantilla->cursos->pluck('id')->toArray())) ? 'checked' : '' }}
                                class="rounded border-gray-300">
                            {{ $curso->nombre }}
                        </label>
                    @empty
                        <p class="text-sm text-gray-400">No hay cursos activos disponibles.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="activa" value="1"
                        {{ old('activa', $plantilla->activa) ? 'checked' : '' }}
                        class="rounded border-gray-300">
                    <span class="text-sm font-medium">Plantilla activa</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Actualizar plantilla
                </button>
                <a href="{{ route('admin.plantillas.show', $plantilla) }}"
                   class="px-5 py-2 rounded border hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
