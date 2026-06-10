<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Detalle del Alumno (Supervisor)</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white p-6 rounded shadow space-y-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-950 mb-1">{{ $alumno->full_name }}</h3>
                <p class="text-sm text-gray-500">Usuario: {{ $alumno->username }}</p>
            </div>

            <div class="border-t pt-4 space-y-3">
                <p><strong>Departamento:</strong> {{ $alumno->department->name ?? 'Sin Departamento' }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($alumno->role) }}</p>
                <p><strong>Miembro desde:</strong> {{ $alumno->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="flex gap-3 border-t pt-4">
                <a href="{{ route('supervisor.alumnos.index') }}"
                   class="px-6 py-2 border rounded hover:bg-gray-50 text-center flex-1">
                    Volver a la Lista
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
