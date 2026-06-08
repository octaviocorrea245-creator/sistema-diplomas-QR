<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Alumnos</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-700">Lista General de Alumnos</h3>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Nombre</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Usuario</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Departamento</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-medium">{{ $alumno->full_name }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $alumno->username }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $alumno->department->name ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <a href="{{ route('supervisor.alumnos.show', $alumno) }}" class="text-blue-500 hover:underline font-medium">Ver Detalle</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No hay alumnos registrados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
