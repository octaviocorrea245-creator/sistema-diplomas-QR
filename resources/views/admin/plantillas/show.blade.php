<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $plantilla->nombre }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.plantillas.edit', $plantilla) }}"
                   class="bg-yellow-500 text-white px-3 py-1.5 rounded text-sm hover:bg-yellow-600">
                    Editar
                </a>
                <a href="{{ route('admin.plantillas.index') }}"
                   class="px-3 py-1.5 rounded border text-sm hover:bg-gray-100">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Información general</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-gray-400">Nombre</dt>
                    <dd class="font-medium">{{ $plantilla->nombre }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Tipo</dt>
                    <dd>
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                            {{ $plantilla->tipo === 'global' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $plantilla->tipo === 'grupo' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $plantilla->tipo === 'individual' ? 'bg-amber-100 text-amber-800' : '' }}
                        ">
                            {{ ucfirst($plantilla->tipo) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Activa</dt>
                    <dd>{{ $plantilla->activa ? 'Sí' : 'No' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Versiones</dt>
                    <dd>{{ $plantilla->versiones->count() }}</dd>
                </div>
                @if($plantilla->descripcion)
                <div class="col-span-2">
                    <dt class="text-gray-400">Descripción</dt>
                    <dd>{{ $plantilla->descripcion }}</dd>
                </div>
                @endif
            </dl>
        </div>

        @if($plantilla->cursos->isNotEmpty())
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Cursos asignados</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($plantilla->cursos as $curso)
                    <li>{{ $curso->nombre }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Versiones</h3>
            @forelse($plantilla->versiones as $version)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <span class="font-medium text-sm">v{{ $version->version }}</span>
                        @if($version->activa)
                            <span class="text-xs bg-green-100 text-green-800 px-1.5 py-0.5 rounded ml-2">Activa</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ $version->created_at->format('d/m/Y') }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Sin versiones aún.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
