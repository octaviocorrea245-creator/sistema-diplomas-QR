<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $cursos->nombre }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">

        <div class="bg-white rounded shadow p-6 space-y-4">

            <div>
                <span class="text-sm text-gray-500">Departamento</span>
                <p class="font-medium">{{ $cursos->departamento->name }}</p>
            </div>

            <div>
                <span class="text-sm text-gray-500">Descripción</span>
                <p>{{ $cursos->descripcion ?? '—' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Horas</span>
                    <p>{{ $cursos->horas ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Estado</span>
                    <p>{{ ucfirst($cursos->estado) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Fecha inicio</span>
                    <p>{{ $cursos->fecha_inicio ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Fecha fin</span>
                    <p>{{ $cursos->fecha_fin ?? '—' }}</p>
                </div>
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <a href="{{ route('admin.cursos.edit', $cursos) }}"
               class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                Editar
            </a>
            <a href="{{ route('admin.cursos.index') }}"
               class="px-5 py-2 rounded border hover:bg-gray-100 transition">
                Volver
            </a>
        </div>

    </div>
</x-app-layout>