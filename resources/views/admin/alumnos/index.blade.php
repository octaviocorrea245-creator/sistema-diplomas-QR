<x-app-layout>

<div class="max-w-6xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Alumnos</h1>
            <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->department->name ?? 'Tu departamento' }}</p>
        </div>
        <a href="{{ route('admin.alumnos.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Nuevo Alumno
        </a>
    </div>

    <form method="GET" action="{{ route('admin.alumnos.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre…"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('admin.alumnos.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50 transition">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    @if($alumnos->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-lg">No se encontraron alumnos.</p>
            @if(request('buscar'))
                <p class="text-sm mt-1">Intenta con otro término de búsqueda.</p>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-gray-500 uppercase text-xs tracking-wide">
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Cursos inscritos</th>
                        <th class="px-5 py-3">Completados</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($alumnos as $alumno)
                        @php
                            $total       = $alumno->cursos->count();
                            $completados = $alumno->cursos->where('pivot.estado', 'completado')->count();
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $alumno->full_name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $total }}</td>
                            <td class="px-5 py-3">
                                <span class="{{ $completados > 0 ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                    {{ $completados }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex gap-3 justify-end">
                                    <a href="{{ route('admin.alumnos.show', $alumno) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium">Ver</a>
                                    <a href="{{ route('admin.alumnos.edit', $alumno) }}"
                                       class="text-yellow-600 hover:text-yellow-800 font-medium">Editar</a>
                                    <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar a \"{{ $alumno->full_name }}\"? Esta acción no se puede deshacer.')" class="inline">
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
        <div class="mt-4">
            {{ $alumnos->links() }}
        </div>
    @endif
</div>

</x-app-layout>
