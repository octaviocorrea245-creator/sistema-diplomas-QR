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
                                <img src="{{ asset($template->background_image) }}" class="w-full h-full object-cover">
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
                                <form action="{{ route('admin.templates.destroy', $template) }}"
                                      method="POST" onsubmit="return confirmAction(event, '¿Eliminar esta plantilla y todos sus elementos?')" class="inline">
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
                    <a href="{{ route('admin.templates.create-for-course', $cursos) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Crear Plantilla
                    </a>
                </div>
            @endif
        </div>

        {{-- Diploma Status / Individual Management --}}
        @if($template)
        @php
            $totalAlumnos = $stats['total'];
            $conDiploma = $diplomasCurso->count();
            $pct = $totalAlumnos > 0 ? round(($conDiploma / $totalAlumnos) * 100) : 0;
        @endphp
        <div class="mt-5 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18h.375a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.375v-.621c0-.832-.672-1.5-1.5-1.5h-.187"/>
                </svg>
                <h3 class="font-semibold text-gray-800 text-sm">Estado de Diplomas</h3>
                <span class="ml-auto text-xs text-gray-500">{{ $conDiploma }} / {{ $totalAlumnos }}</span>
            </div>
            <div class="p-5">
                {{-- Progress bar --}}
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs text-gray-500">Cobertura</span>
                    <span class="text-xs font-semibold text-gray-700">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>

                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <form action="{{ route('admin.diplomas.mass.generate-quick', $cursos) }}" method="POST"
                          onsubmit="return confirmAction(event, '¿Generar diplomas para TODOS los alumnos de {{ $cursos->nombre }}? Fecha de emisión: hoy.')" class="inline">
                        @csrf
                        <button type="submit"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18h.375a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.375v-.621c0-.832-.672-1.5-1.5-1.5h-.187"/>
                            </svg>
                            Generar diplomas
                        </button>
                    </form>
                    @if($conDiploma > 0)
                    <form action="{{ route('admin.diplomas.mass.regenerate', $cursos) }}" method="POST"
                          onsubmit="return confirmAction(event, '¿Regenerar TODOS los {{ $conDiploma }} diplomas de este curso? Los PDFs se eliminarán y se crearán nuevos. El QR y token se mantendrán.')" class="inline">
                        @csrf
                        <button type="submit"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-white text-amber-600 border border-amber-300 rounded-lg hover:bg-amber-50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                            </svg>
                            Regenerar todos ({{ $conDiploma }})
                        </button>
                    </form>
                    <a href="{{ route('admin.diplomas.mass.download-combined', $cursos) }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-white text-blue-600 border border-blue-300 rounded-lg hover:bg-blue-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Descargar todo en PDF
                    </a>
                    <button type="button" onclick="openFirmaModal()"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-white text-purple-600 border border-purple-300 rounded-lg hover:bg-purple-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Activar E-Firma
                    </button>
                    @endif
                </div>

                {{-- E-Firma Modal --}}
                <div id="firmaModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
                    <div style="background:#fff;border-radius:16px;width:90%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;">
                        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;">
                            <h3 style="font-size:1rem;font-weight:700;color:#0D1B35;margin:0;">Firma Electrónica</h3>
                            <button type="button" onclick="closeFirmaModal()" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;line-height:1;">&times;</button>
                        </div>
                        <div id="firmaModalBody" style="padding:1.5rem;min-height:100px;">
                            @if($firmantes->isEmpty())
                                <div style="text-align:center;padding:1.5rem 0;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;color:#cbd5e1;margin:0 auto 1rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    <p style="font-size:0.95rem;color:#64748b;margin:0 0 0.25rem;">No hay e.firma registrada</p>
                                    <p style="font-size:0.8rem;color:#94a3b8;margin:0;">Registra un firmante en la sección de Firmantes para activar la firma electrónica.</p>
                                </div>
                            @else
                                @php $singleF = $firmantes->count() === 1 ? $firmantes->first() : null; @endphp
                                <form id="firmaForm" method="POST"
                                      @if($singleF) action="{{ route('admin.firmantes.firmar-masivo', $singleF) }}" @else action="" @endif
                                      onsubmit="return submitFirma(event)">
                                    @csrf
                                    <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                                    @if($firmantes->count() > 1)
                                        <div style="margin-bottom:1rem;">
                                            <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:0.35rem;">Seleccionar Firmante</label>
                                            <select id="firmanteSelect" onchange="updateFirmaForm()" style="width:100%;padding:0.55rem 0.75rem;border:1px solid #DDE3EF;border-radius:10px;font-size:0.9rem;outline:none;">
                                                <option value="">Selecciona un firmante...</option>
                                                @foreach($firmantes as $f)
                                                    <option value="{{ $f->id }}">{{ $f->nombre }} — {{ $f->cargo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#F8FAFC;border-radius:10px;border:1px solid #E2E8F0;">
                                            <p style="font-size:0.85rem;font-weight:600;color:#1e293b;margin:0 0 0.2rem;">{{ $singleF->nombre }}</p>
                                            <p style="font-size:0.75rem;color:#64748b;margin:0;">{{ $singleF->cargo }}</p>
                                        </div>
                                    @endif
                                    <div style="margin-bottom:1rem;">
                                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:0.35rem;">Contraseña de la e.firma</label>
                                        <input type="password" name="password" id="firmaPassword" required
                                               style="width:100%;padding:0.55rem 0.75rem;border:1px solid #DDE3EF;border-radius:10px;font-size:0.9rem;outline:none;"
                                               placeholder="Ingresa la contraseña del certificado">
                                    </div>
                                    <button type="submit"
                                       class="inline-flex items-center justify-center gap-1.5 w-full px-4 py-2.5 text-sm font-semibold bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 shadow-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                        Firmar {{ $conDiploma }} diploma(s)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <script>
                    function openFirmaModal() {
                        document.getElementById('firmaModal').style.display = 'flex';
                    }
                    function closeFirmaModal() {
                        document.getElementById('firmaModal').style.display = 'none';
                    }
                    @if($firmantes->count() > 1)
                    var firmaBaseUrl = '{{ url("admin/firmantes") }}';
                    function updateFirmaForm() {
                        var sel = document.getElementById('firmanteSelect');
                        var id = sel.value;
                        var form = document.getElementById('firmaForm');
                        if (id) {
                            form.action = firmaBaseUrl + '/' + id + '/firmar-masivo';
                        } else {
                            form.action = '';
                        }
                    }
                    function submitFirma(e) {
                        var sel = document.getElementById('firmanteSelect');
                        if (!sel.value) { alert('Selecciona un firmante.'); e.preventDefault(); return false; }
                        return true;
                    }
                    @else
                    function submitFirma(e) {
                        return true;
                    }
                    @endif
                    document.addEventListener('click', function(e) {
                        var modal = document.getElementById('firmaModal');
                        if (e.target === modal) closeFirmaModal();
                    });
                </script>

                {{-- Student list --}}
                <div class="mt-3 border border-gray-200 rounded-lg max-h-72 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="text-left px-3 py-2 text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                <th class="text-center px-3 py-2 text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="text-right px-3 py-2 text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($alumnos as $alumno)
                                @php $d = $diplomasCurso->get($alumno->id); @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-gray-800 text-xs">{{ $alumno->full_name }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($d)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2 py-0.5 rounded-full">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                {{ $d->folio }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        @if($d)
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.diplomas.mass.download', $d) }}"
                                                   class="text-xs font-medium text-blue-600 hover:text-blue-800" title="Descargar PDF">Descargar</a>
                                                <form action="{{ route('admin.diplomas.mass.regenerate-individual', $d) }}"
                                                      method="POST" onsubmit="return confirmAction(event, '¿Regenerar diploma de {{ $alumno->full_name }}? Se mantendrá el mismo QR.')" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-medium text-amber-600 hover:text-amber-800">Regenerar</button>
                                                </form>
                                            </div>
                                        @else
                                            <form action="{{ route('admin.diplomas.mass.generate-individual', ['curso' => $cursos, 'alumno' => $alumno]) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800">Generar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($totalAlumnos === 0)
                    <div class="text-center py-4 text-sm text-gray-400">No hay alumnos inscritos en este curso.</div>
                @endif
            </div>
        </div>
        @endif

    </div>
</x-app-layout>