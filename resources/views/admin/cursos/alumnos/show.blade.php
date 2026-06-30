{{-- resources/views/admin/cursos/alumnos/show.blade.php --}}
<x-app-layout>
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="hover:text-indigo-600">Alumnos del curso</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $alumno->display_name }}</span>
    </nav>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Perfil --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-5 flex items-start justify-between">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-lg font-bold flex-shrink-0">
                {{ strtoupper(substr($alumno->display_name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ $alumno->display_name }}</h1>
                @if($alumno->full_name && $alumno->full_name !== $alumno->username)
                    <p class="text-sm text-gray-400">@{{ $alumno->username }}</p>
                @endif
                <p class="text-sm text-gray-500 mt-0.5">{{ $alumno->department->name ?? '—' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}"
               class="text-sm border border-gray-300 px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                Editar inscripción
            </a>
        </div>
    </div>

    {{-- Datos de inscripcion --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-4">Inscripción en {{ $curso->nombre }}</p>

        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-400">Estado</dt>
                <dd class="mt-0.5">
                    @include('partials.estado-badge', ['estado' => $inscripcion->pivot->estado])
                </dd>
            </div>
            <div>
                <dt class="text-gray-400">Fecha de inscripción</dt>
                <dd class="mt-0.5 text-gray-700">{{ $inscripcion->pivot->created_at?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400">Fecha de completado</dt>
                <dd class="mt-0.5 text-gray-700">
                    {{ $inscripcion->pivot->fecha_completado
                        ? \Carbon\Carbon::parse($inscripcion->pivot->fecha_completado)->format('d/m/Y')
                        : '—' }}
                </dd>
            </div>
            <div>
                <dt class="text-gray-400">Horas del curso</dt>
                <dd class="mt-0.5 text-gray-700">{{ $curso->horas }} h</dd>
            </div>
        </dl>
    </div>

</div>
</x-app-layout>