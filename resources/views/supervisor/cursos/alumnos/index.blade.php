<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Alumnos Inscritos - {{ $curso->nombre }}</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-700">Listado de Alumnos</h3>
            <p class="text-sm text-gray-500">Curso: {{ $curso->nombre }} ({{ $curso->departamento->name ?? 'Sin Departamento' }})</p>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Nombre Alumno</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Usuario</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Estado del Curso</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Fecha de Completación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-medium">{{ $alumno->full_name }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $alumno->username }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $alumno->pivot->estado === 'completado' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $alumno->pivot->estado === 'inscrito' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $alumno->pivot->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($alumno->pivot->estado) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2">
                        {{ $alumno->pivot->fecha_completado ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No hay alumnos inscritos en este curso aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            <a href="{{ route('supervisor.cursos.index') }}"
               class="inline-block px-6 py-2 border rounded hover:bg-gray-50 bg-white">
                Volver a la Lista de Cursos
            </a>
        </div>
    </div>
</x-app-layout>
