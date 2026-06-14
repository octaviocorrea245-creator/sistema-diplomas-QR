<x-app-layout>

<div class="max-w-lg mx-auto px-4 py-8">

    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.alumnos.index') }}" class="hover:text-indigo-600">Alumnos</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.alumnos.show', $alumno) }}" class="hover:text-indigo-600">{{ $alumno->full_name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Editar</span>
    </nav>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">Editar Alumno</h1>

        <form method="POST" action="{{ route('admin.alumnos.update', $alumno) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                <input type="text" name="full_name" value="{{ old('full_name', $alumno->full_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('full_name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                    Actualizar
                </button>
                <a href="{{ route('admin.alumnos.show', $alumno) }}" class="px-5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</div>

</x-app-layout>
