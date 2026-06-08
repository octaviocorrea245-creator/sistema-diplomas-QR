<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Crear Curso</h2>
    </x-slot>

    <div class="py-8 max-w-md mx-auto px-4">
        <form action="{{ route('admin.cursos.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Curso</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                @error('nombre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Departamento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                <select name="departamento_id" required
                        class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    <option value="">-- Selecciona un departamento --</option>
                    @foreach($departamentos as $dept)
                        <option value="{{ $dept->id }}"
                            {{ old('departamento_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('departamento_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Descripcion --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                @error('descripcion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Horas --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Duración (Horas)</label>
                <input type="number" name="horas" value="{{ old('horas') }}" min="1"
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                @error('horas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Fechas --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                           class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    @error('fecha_inicio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                           class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    @error('fecha_fin') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Estado --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Estado</label>
                <select name="estado" required
                        class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('estado') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Crear Curso
                </button>
                <a href="{{ route('admin.cursos.index') }}"
                   class="px-6 py-2 border rounded hover:bg-gray-50 text-center flex-1">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
