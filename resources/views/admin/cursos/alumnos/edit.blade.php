{{-- resources/views/admin/cursos/alumnos/edit.blade.php --}}
<x-app-layout>
<div class="max-w-xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="hover:text-indigo-600">Alumnos del curso</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}" class="hover:text-indigo-600">
            {{ $alumno->display_name }}
        </a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Editar</span>
    </nav>

    <h1 class="text-2xl font-semibold text-gray-800 mb-1">Editar inscripción</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $alumno->display_name }} · {{ $curso->nombre }}</p>

    <form method="POST" action="{{ route('admin.cursos.alumnos.update', [$curso, $alumno]) }}"
          x-data="{ estado: '{{ old('estado', $inscripcion->pivot->estado) }}' }">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" x-model="estado"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="inscrito"   :selected="estado === 'inscrito'">Inscrito</option>
                    <option value="en_curso"   :selected="estado === 'en_curso'">En curso</option>
                    <option value="completado" :selected="estado === 'completado'">Completado</option>
                    <option value="baja"       :selected="estado === 'baja'">Baja</option>
                </select>
                @error('estado')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="estado === 'completado'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de completado</label>
                <input type="date" name="fecha_completado"
                       value="{{ old('fecha_completado', $inscripcion->pivot->fecha_completado?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('fecha_completado')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700 transition font-medium">
                Guardar cambios
            </button>
            <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}"
               class="px-5 py-2 rounded-lg text-sm border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
</x-app-layout>
