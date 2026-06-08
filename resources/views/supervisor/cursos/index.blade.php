<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Cursos (Supervisor)</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-700">Lista General de Cursos</h3>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Nombre</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Departamento</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Horas</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Estado</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cursos as $curso)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-medium">{{ $curso->nombre }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $curso->departamento->name ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $curso->horas ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($curso->estado) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2 flex gap-3">
                        <a href="{{ route('supervisor.cursos.show', $curso) }}" class="text-blue-500 hover:underline">Ver Detalle</a>
                        <a href="{{ route('supervisor.cursos.alumnos', $curso) }}" class="text-amber-700 hover:underline font-medium">Ver Alumnos</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No hay cursos registrados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
