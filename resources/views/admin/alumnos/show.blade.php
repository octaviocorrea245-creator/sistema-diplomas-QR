<x-app-layout>

<div class="max-w-4xl mx-auto px-4 py-8">

    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.alumnos.index') }}" class="hover:text-indigo-600">Alumnos</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $alumno->display_name }}</span>
    </nav>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xl font-bold shrink-0">
                    {{ strtoupper(substr($alumno->display_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">{{ $alumno->full_name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $alumno->department->name ?? '—' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.alumnos.edit', $alumno) }}"
                   class="px-3 py-1.5 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    Editar
                </a>
                <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar a \"{{ $alumno->full_name }}\"?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-semibold text-gray-700 mb-3">Cursos</h2>

    @if($alumno->cursos->isEmpty())
        <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-200">
            <p>Este alumno no tiene cursos inscritos.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($alumno->cursos as $curso)
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">{{ $curso->nombre }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $curso->horas }} horas ·
                            {{ $curso->fecha_inicio?->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') }}
                        </p>
                        @if($curso->pivot->fecha_completado)
                            <p class="text-xs text-green-600 mt-0.5">
                                Completado el {{ \Carbon\Carbon::parse($curso->pivot->fecha_completado)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @include('partials.estado-badge', ['estado' => $curso->pivot->estado])
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

</x-app-layout>
