<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Plantillas</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Lista de Plantillas</h3>
            <a href="{{ route('admin.plantillas.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nueva Plantilla
            </a>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Nombre</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Tipo</th>
                    <th class="border border-gray-200 px-4 py-2 text-center">Versiones</th>
                    <th class="border border-gray-200 px-4 py-2 text-center">Activa</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plantillas as $plantilla)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-medium">{{ $plantilla->nombre }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                            {{ $plantilla->tipo === 'global' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $plantilla->tipo === 'grupo' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $plantilla->tipo === 'individual' ? 'bg-amber-100 text-amber-800' : '' }}
                        ">
                            {{ ucfirst($plantilla->tipo) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-center">{{ $plantilla->versiones->count() }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-center">
                        @if($plantilla->activa)
                            <span class="text-green-600 font-semibold">Sí</span>
                        @else
                            <span class="text-red-500 font-semibold">No</span>
                        @endif
                    </td>
                    <td class="border border-gray-200 px-4 py-2 flex gap-3">
                        <a href="{{ route('admin.plantillas.show', $plantilla) }}" class="text-blue-500 hover:underline">Ver</a>
                        <a href="{{ route('admin.plantillas.edit', $plantilla) }}" class="text-yellow-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.plantillas.destroy', $plantilla) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta plantilla?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No hay plantillas creadas aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
