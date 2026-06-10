<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Curso
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">

        <p class="text-sm text-gray-500 mb-6">
            Departamento: <span class="font-semibold text-gray-700">{{ auth()->user()->department->name }}</span>
        </p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 rounded p-3 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cursos.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nombre del curso <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Horas</label>
                <input type="number" name="horas" value="{{ old('horas') }}" min="1"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Estado <span class="text-red-500">*</span></label>
                <select name="estado"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (['borrador', 'activo', 'finalizado', 'cancelado'] as $estado)
                        <option value="{{ $estado }}" {{ old('estado', 'borrador') === $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Guardar curso
                </button>
                <a href="{{ route('admin.cursos.index') }}"
                   class="px-5 py-2 rounded border hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</x-app-layout>