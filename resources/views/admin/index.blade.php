<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Panel de Administración</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.cursos.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:shadow-md transition">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['cursos'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Cursos</p>
            </a>
            <a href="{{ route('admin.alumnos.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:shadow-md transition">
                <p class="text-2xl font-bold text-green-600">{{ $stats['alumnos'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Alumnos</p>
            </a>
            <a href="{{ route('admin.plantillas.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:shadow-md transition">
                <p class="text-2xl font-bold text-purple-600">{{ $stats['plantillas'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Plantillas</p>
            </a>
            <a href="{{ route('admin.diplomas.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:shadow-md transition">
                <p class="text-2xl font-bold text-amber-600">{{ $stats['diplomas'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Diplomas</p>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-100">
            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-700">Últimos cursos</h3>
                <a href="{{ route('admin.cursos.index') }}" class="text-sm text-blue-600 hover:underline">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($ultimosCursos as $curso)
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $curso->nombre }}</p>
                            <p class="text-xs text-gray-400">{{ $curso->alumnos()->count() }} alumnos</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                            {{ $curso->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $curso->estado === 'borrador' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $curso->estado === 'finalizado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $curso->estado === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($curso->estado) }}
                        </span>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-gray-400 text-sm">No hay cursos aún.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
