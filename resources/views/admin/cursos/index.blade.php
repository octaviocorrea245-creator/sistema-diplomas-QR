<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Cursos</h2>
    </x-slot>

    @php
        $viewMode = request('view', 'grid');
    @endphp

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Mis Cursos</h3>
                <p class="text-sm text-gray-500">{{ $cursos->count() }} curso(s) en total</p>
            </div>
            <a href="{{ route('admin.cursos.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nuevo Curso
            </a>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('admin.cursos.index') }}" class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1 block">Buscar</label>
                    <div class="relative">
                       
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por nombre..."
                               class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="w-44">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1 block">Estado</label>
                    <select name="estado"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="finalizado" {{ request('estado') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="cancelado" {{ request('estado') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700 transition">
                        Filtrar
                    </button>
                    @if(request('search') || request('estado'))
                        <a href="{{ route('admin.cursos.index') }}"
                           class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                            Limpiar
                        </a>
                    @endif
                </div>
                <div class="flex ml-auto border border-gray-200 rounded-lg overflow-hidden">
                    <a href="{{ route('admin.cursos.index', array_merge(request()->query(), ['view' => 'grid'])) }}"
                       class="px-3 py-2 text-sm {{ $viewMode === 'grid' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.cursos.index', array_merge(request()->query(), ['view' => 'list'])) }}"
                       class="px-3 py-2 text-sm {{ $viewMode === 'list' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </form>

        {{-- Results --}}
        @if($cursos->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <p class="text-sm text-gray-400">No hay cursos que coincidan con tu búsqueda.</p>
                @if(request('search') || request('estado'))
                    <a href="{{ route('admin.cursos.index') }}" class="mt-2 inline-block text-xs text-blue-600 hover:underline">Limpiar filtros</a>
                @endif
            </div>
        @elseif($viewMode === 'list')
            {{-- List view --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-left text-gray-500 uppercase text-xs tracking-wide">
                            <th class="px-5 py-3">Nombre</th>
                            <th class="px-5 py-3">Departamento</th>
                            <th class="px-5 py-3">Horas</th>
                            <th class="px-5 py-3">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cursos as $curso)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $curso->nombre }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $curso->departamento->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $curso->horas ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-block px-2.5 py-0.5 text-xs rounded-full font-semibold
                                        {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ ucfirst($curso->estado) }}
                                    </span>
                                    @if($curso->template)
                                        <span class="ml-1.5 inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded-full font-medium">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                            Plantilla
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex gap-3 justify-end">
                                        <a href="{{ route('admin.cursos.show', $curso) }}" class="text-blue-600 hover:text-blue-800 font-medium">Ver</a>
                                        <a href="{{ route('admin.cursos.edit', $curso) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Editar</a>
                                        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Alumnos</a>
                                        <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar este curso?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Grid / Card view --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cursos as $curso)
                <div class="bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900 leading-tight truncate text-sm">
                                {{ $curso->nombre }}
                            </h3>
                            <span class="shrink-0 px-2 py-0.5 text-xs rounded-full font-medium
                                {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                {{ ucfirst($curso->estado) }}
                            </span>
                        </div>
                        @if($curso->template)
                            <div class="mb-2">
                                <span class="inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full font-medium">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    Tiene plantilla
                                </span>
                            </div>
                        @endif

                        <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $curso->descripcion ?: 'Sin descripción' }}</p>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 mb-3">
                            <span>{{ $curso->departamento->name ?? '—' }}</span>
                            <span>{{ $curso->horas ? $curso->horas.' h' : '—' }}</span>
                            @if($curso->fecha_inicio)
                                <span>{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <a href="{{ route('admin.cursos.show', $curso) }}" class="text-xs text-blue-600 hover:underline">Ver</a>
                            <a href="{{ route('admin.cursos.edit', $curso) }}" class="text-xs text-yellow-600 hover:underline">Editar</a>
                            <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="text-xs text-indigo-600 hover:underline">Alumnos</a>
                            <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este curso?')" class="ml-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
