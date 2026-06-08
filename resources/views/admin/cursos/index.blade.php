<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Cursos</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Lista de Cursos</h3>
            <a href="{{ route('admin.cursos.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nuevo Curso
            </a>
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
                        <a href="{{ route('admin.cursos.show', $curso) }}" class="text-blue-500 hover:underline">Ver</a>
                        <a href="{{ route('admin.cursos.edit', $curso) }}" class="text-yellow-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este curso?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No hay cursos creados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
