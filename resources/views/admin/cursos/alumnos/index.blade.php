{{-- resources/views/admin/cursos/alumnos/index.blade.php --}}

<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">

    @php
        $viewMode = request('view', 'list');
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.cursos.index') }}" class="hover:text-indigo-600">Cursos</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}" class="hover:text-indigo-600">{{ $curso->nombre }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Alumnos</span>
    </nav>

    {{-- Encabezado --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Alumnos inscritos</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $curso->nombre }} · {{ $alumnos->total() }} alumno(s)</p>
        </div>
        <a href="{{ route('admin.cursos.alumnos.create', $curso) }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Agregar alumno
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.cursos.alumnos.index', $curso) }}" class="mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1 block">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por nombre o usuario..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="w-44">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1 block">Estado</label>
                <select name="estado"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="inscrito" {{ request('estado') === 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                    <option value="en_curso" {{ request('estado') === 'en_curso' ? 'selected' : '' }}>En curso</option>
                    <option value="completado" {{ request('estado') === 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="baja" {{ request('estado') === 'baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">
                    Filtrar
                </button>
                @if(request('search') || request('estado'))
                    <a href="{{ route('admin.cursos.alumnos.index', $curso) }}"
                       class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                        Limpiar
                    </a>
                @endif
            </div>
            <div class="flex ml-auto border border-gray-200 rounded-lg overflow-hidden">
                <a href="{{ route('admin.cursos.alumnos.index', array_merge(request()->query(), ['curso' => $curso->id, 'view' => 'grid'])) }}"
                   class="px-3 py-2 text-sm {{ $viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </a>
                <a href="{{ route('admin.cursos.alumnos.index', array_merge(request()->query(), ['curso' => $curso->id, 'view' => 'list'])) }}"
                   class="px-3 py-2 text-sm {{ $viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </form>

    {{-- Cards / Table --}}
    @if($alumnos->isEmpty())
        <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/>
            </svg>
            <p class="text-sm text-gray-400 mb-2">Ningún alumno inscrito aún.</p>
            <a href="{{ route('admin.cursos.alumnos.create', $curso) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.679 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.966-1.582L5 18.72m5.5-12A3.5 3.5 0 0117.5 9v.75C17.5 11.55 16.55 12.5 15.25 12.5h-4.5A3.5 3.5 0 007.5 9v-.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a2.25 2.25 0 011.5-2.25h13.5a2.25 2.25 0 011.5 2.25v10.5a2.25 2.25 0 01-1.5 2.25h-13.5a2.25 2.25 0 01-1.5-2.25V6.75z"/>
                </svg>
                + Agregar el primero
            </a>
        </div>
    @elseif($viewMode === 'list')
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-gray-500 uppercase text-xs tracking-wide">
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3">Inscripción</th>
                        <th class="px-5 py-3">Completado</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($alumnos as $alumno)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 font-medium text-gray-800">{{ $alumno->display_name }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $alumno->username }}</td>
                            <td class="px-5 py-4">
                                @include('partials.estado-badge', ['estado' => $alumno->pivot->estado])
                            </td>
                            <td class="px-5 py-4 text-gray-500">{{ $alumno->pivot->created_at?->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $alumno->pivot->fecha_completado ? \Carbon\Carbon::parse($alumno->pivot->fecha_completado)->format('d/m/Y') : '—' }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex gap-3 justify-end">
                                    <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver</a>
                                    <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Editar</a>
                                    <form method="POST" action="{{ route('admin.cursos.alumnos.destroy', [$curso, $alumno]) }}"
                                          onsubmit="return confirm('¿Dar de baja a este alumno del curso?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Baja</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($alumnos as $alumno)
                <div class="bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $alumno->display_name }}
                                </h3>
                                @if($alumno->full_name && $alumno->full_name !== $alumno->username)
                                    <p class="text-xs text-gray-500 truncate">{{ $alumno->username }}</p>
                                @endif
                            </div>
                            @include('partials.estado-badge', ['estado' => $alumno->pivot->estado])
                        </div>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 mb-3">
                            <span>Inscrito {{ $alumno->pivot->created_at?->format('d/m/Y') }}</span>
                            <span>{{ $alumno->pivot->fecha_completado ? 'Completado '.\Carbon\Carbon::parse($alumno->pivot->fecha_completado)->format('d/m/Y') : '—' }}</span>
                        </div>

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <a href="{{ route('admin.cursos.alumnos.show', [$curso, $alumno]) }}"
                               class="text-xs text-indigo-600 hover:underline">Ver</a>
                            <a href="{{ route('admin.cursos.alumnos.edit', [$curso, $alumno]) }}"
                               class="text-xs text-yellow-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.cursos.alumnos.destroy', [$curso, $alumno]) }}"
                                  onsubmit="return confirm('¿Dar de baja a este alumno del curso?')" class="ml-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Baja</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($alumnos->hasPages())
        <div class="mt-6">
            {{ $alumnos->links() }}
        </div>
    @endif

</div>
</x-app-layout>
