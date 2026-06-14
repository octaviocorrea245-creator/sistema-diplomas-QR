<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $cursos->nombre }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">

        {{-- Info del curso --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4 mb-5">

            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Departamento</span>
                    <p class="font-medium text-gray-900 mt-0.5 text-sm">{{ $cursos->departamento->name }}</p>
                </div>
                <span class="shrink-0 px-2.5 py-0.5 text-xs rounded-full font-semibold
                    {{ $cursos->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $cursos->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $cursos->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $cursos->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ ucfirst($cursos->estado) }}
                </span>
            </div>

            <div>
                <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Descripción</span>
                <p class="text-sm text-gray-700 mt-0.5">{{ $cursos->descripcion ?? '—' }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Horas</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $cursos->horas ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Inicio</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $cursos->fecha_inicio ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Fin</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $cursos->fecha_fin ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Alumnos</span>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- Student stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'inscrito']) }}"
               class="bg-white rounded-xl border border-gray-200 p-3 hover:shadow-md transition-shadow">
                <span class="text-lg font-bold text-blue-600">{{ $stats['inscrito'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Inscritos</p>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'en_curso']) }}"
               class="bg-white rounded-xl border border-gray-200 p-3 hover:shadow-md transition-shadow">
                <span class="text-lg font-bold text-yellow-600">{{ $stats['en_curso'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">En curso</p>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'completado']) }}"
               class="bg-white rounded-xl border border-gray-200 p-3 hover:shadow-md transition-shadow">
                <span class="text-lg font-bold text-green-600">{{ $stats['completado'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Completados</p>
            </a>
            <a href="{{ route('admin.cursos.alumnos.index', ['curso' => $cursos, 'estado' => 'baja']) }}"
               class="bg-white rounded-xl border border-gray-200 p-3 hover:shadow-md transition-shadow">
                <span class="text-lg font-bold text-red-600">{{ $stats['baja'] }}</span>
                <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide">Bajas</p>
            </a>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.cursos.alumnos.index', $cursos) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.679 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.966-1.582L5 18.72m5.5-12A3.5 3.5 0 0117.5 9v.75C17.5 11.55 16.55 12.5 15.25 12.5h-4.5A3.5 3.5 0 007.5 9v-.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a2.25 2.25 0 011.5-2.25h13.5a2.25 2.25 0 011.5 2.25v10.5a2.25 2.25 0 01-1.5 2.25h-13.5a2.25 2.25 0 01-1.5-2.25V6.75z"/>
                </svg>
                Gestionar Alumnos
            </a>
            <a href="{{ route('admin.cursos.edit', $cursos) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21h-9.5A2.25 2.25 0 014 18.75V14"/>
                </svg>
                Editar Curso
            </a>
            <a href="{{ route('admin.cursos.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                </svg>
                Volver
            </a>
        </div>

        {{-- Diploma Template --}}
        @php $template = $cursos->template; @endphp
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                                <img src="{{ Storage::url($template->background_image) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="shrink-0 w-36 h-24 rounded-lg border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400 text-xs">Sin fondo</div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-gray-900 truncate">{{ $template->nombre }}</h4>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-500">
                                <span>{{ $template->canvas_width }} × {{ $template->canvas_height }} px</span>
                                <span>{{ $template->elements->count() }} elemento(s)</span>
                                <span>Creado {{ $template->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('admin.templates.editor', $template) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                    Diseñar
                                </a>
                                <a href="{{ route('admin.templates.show', $template) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-white text-gray-700 border border-gray-200 rounded-md hover:bg-gray-50 transition">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Ver
                                </a>
                                <a href="{{ route('admin.diplomas.mass.show', $cursos) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18h.375a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.375v-.621c0-.832-.672-1.5-1.5-1.5h-.187a48.415 48.415 0 01-4.126 0h-.187c-.828 0-1.5.668-1.5 1.5v.621m0 0V21h9v-2.625M12 3.75h4.5a1.5 1.5 0 011.5 1.5v1.5c0 .828-.672 1.5-1.5 1.5H12a1.5 1.5 0 01-1.5-1.5v-1.5c0-.828.672-1.5 1.5-1.5z"/></svg>
                                    Generar Diplomas
                                </a>
                                <form action="{{ route('admin.templates.destroy', $template) }}"
                                      method="POST" onsubmit="return confirm('¿Eliminar esta plantilla y todos sus elementos?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-white text-red-600 border border-red-200 rounded-md hover:bg-red-50 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-5 text-center">
                    <p class="text-sm text-gray-500 mb-3">Este curso aún no tiene una plantilla de diploma.</p>
                    <a href="{{ route('admin.templates.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Crear Plantilla
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>