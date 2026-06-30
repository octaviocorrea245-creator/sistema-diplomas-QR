<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Detalle del Curso (Supervisor)</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white p-6 rounded shadow space-y-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-950 mb-1">{{ $curso->nombre }}</h3>
                <p class="text-sm text-gray-500">Departamento: {{ $curso->departamento->name ?? '—' }}</p>
            </div>

            <div class="border-t pt-4 space-y-3">
                <p><strong>Descripción:</strong> {{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>
                <p><strong>Duración:</strong> {{ $curso->horas ? $curso->horas . ' horas' : '—' }}</p>
                <p><strong>Fecha de Inicio:</strong> {{ $curso->fecha_inicio ?? '—' }}</p>
                <p><strong>Fecha de Fin:</strong> {{ $curso->fecha_fin ?? '—' }}</p>
                <p><strong>Estado:</strong>
                    <span class="px-2 py-1 text-xs rounded-full font-semibold
                        {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ ucfirst($curso->estado) }}
                    </span>
                </p>
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold text-lg text-gray-900 mb-3">Alumnos Inscritos ({{ $curso->users->count() }})</h4>
                @if($curso->users->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($curso->users as $alumno)
                            <li class="py-2 flex justify-between text-sm">
                                <span>{{ $alumno->full_name }}</span>
                                <span class="text-gray-500 font-medium">Estado: {{ ucfirst($alumno->pivot->estado) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No hay alumnos inscritos en este curso.</p>
                @endif
            </div>

            <div class="flex gap-3 border-t pt-4">
                <a href="{{ route('supervisor.cursos.index') }}"
                   class="px-6 py-2 border rounded hover:bg-gray-50 text-center flex-1">
                    Volver a la Lista
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
