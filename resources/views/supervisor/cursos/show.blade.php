<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $curso->nombre }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">

        {{-- Info del curso --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4 mb-5">

            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Departamento</span>
                    <p class="font-medium text-gray-900 mt-0.5 text-sm">{{ $curso->departamento->name ?? '—' }}</p>
                </div>
                <span class="shrink-0 px-2.5 py-0.5 text-xs rounded-full font-semibold
                    {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ ucfirst($curso->estado) }}
                </span>
            </div>

            <div>
                <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Descripción</span>
                <p class="text-sm text-gray-700 mt-0.5">{{ $curso->descripcion ?? '—' }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Horas</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $curso->horas ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Inicio</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $curso->fecha_inicio ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Fin</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $curso->fecha_fin ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Alumnos</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- Student stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            <div class="bg-white rounded-xl border border-gray-200 p-3">
                <span class="text-lg font-bold text-blue-600">{{ $stats['inscrito'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Inscritos</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3">
                <span class="text-lg font-bold text-yellow-600">{{ $stats['en_curso'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">En curso</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3">
                <span class="text-lg font-bold text-green-600">{{ $stats['completado'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Completados</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3">
                <span class="text-lg font-bold text-red-600">{{ $stats['baja'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Bajas</p>
            </div>
        </div>

        {{-- Alumnos list --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.679 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.966-1.582L5 18.72m5.5-12A3.5 3.5 0 0117.5 9v.75C17.5 11.55 16.55 12.5 15.25 12.5h-4.5A3.5 3.5 0 007.5 9v-.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a2.25 2.25 0 011.5-2.25h13.5a2.25 2.25 0 011.5 2.25v10.5a2.25 2.25 0 01-1.5 2.25h-13.5a2.25 2.25 0 01-1.5-2.25V6.75z"/>
                </svg>
                <h3 class="font-semibold text-gray-800 text-sm">Alumnos Inscritos ({{ $stats['total'] }})</h3>
                <a href="{{ route('supervisor.cursos.alumnos.index', $curso) }}"
                   class="ml-auto text-xs font-medium text-blue-600 hover:text-blue-800">
                    Ver todos &rarr;
                </a>
            </div>

            @if($stats['total'] > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($curso->users as $alumno)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                @if($alumno->avatar_url)
                                    <img src="{{ $alumno->avatar_url }}" alt="Avatar"
                                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold uppercase">
                                        {{ substr($alumno->full_name, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $alumno->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $alumno->email ?? '' }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                {{ $alumno->pivot->estado === 'inscrito' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $alumno->pivot->estado === 'en_curso' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $alumno->pivot->estado === 'completado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $alumno->pivot->estado === 'baja' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst($alumno->pivot->estado) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-sm text-gray-400">No hay alumnos inscritos en este curso.</div>
            @endif
        </div>

        {{-- Diploma Template Preview --}}
        @php $template = $curso->template; @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-5">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                <h3 class="font-semibold text-gray-800 text-sm">Plantilla de Diploma</h3>
                @if($template)
                    <span class="ml-auto text-xs text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full font-medium">Creada</span>
                @else
                    <span class="ml-auto text-xs text-gray-500 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-full">Sin plantilla</span>
                @endif
            </div>

            @if($template)
                <div class="p-5">
                    <div class="flex gap-5">
                        @if($template->background_image)
                            <div class="shrink-0 w-36 h-24 rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                <img src="{{ asset($template->background_image) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="shrink-0 w-36 h-24 rounded-lg border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400 text-xs">Sin fondo</div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-gray-900 truncate">{{ $template->nombre }}</h4>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-500">
                                <span>{{ $template->canvas_width }} &times; {{ $template->canvas_height }} px</span>
                                <span>{{ $template->elements->count() }} elemento(s)</span>
                                <span>Creado {{ $template->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('templates.preview', $template) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Vista previa
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="p-5 text-center">
                    <p class="text-sm text-gray-500">Este curso aún no tiene una plantilla de diploma.</p>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-2 mt-5">
            <a href="{{ route('supervisor.cursos.alumnos.index', $curso) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.679 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.966-1.582L5 18.72m5.5-12A3.5 3.5 0 0117.5 9v.75C17.5 11.55 16.55 12.5 15.25 12.5h-4.5A3.5 3.5 0 007.5 9v-.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a2.25 2.25 0 011.5-2.25h13.5a2.25 2.25 0 011.5 2.25v10.5a2.25 2.25 0 01-1.5 2.25h-13.5a2.25 2.25 0 01-1.5-2.25V6.75z"/>
                </svg>
                Gestionar Alumnos
            </a>
            <a href="{{ route('supervisor.cursos.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                </svg>
                Volver
            </a>
        </div>

    </div>
</x-app-layout>
