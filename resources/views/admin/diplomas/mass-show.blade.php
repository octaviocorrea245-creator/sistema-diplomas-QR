<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Diplomas Generados</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.diplomas.mass.download-all', $curso) }}"
                   class="px-3 py-1.5 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                    Descargar ZIP
                </a>
                <a href="{{ route('admin.diplomas.mass.create') }}"
                   class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">
                    Nueva generación
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded-lg p-4 mb-4">
            <div class="text-sm text-gray-500">
                Curso: <strong>{{ $curso->nombre }}</strong> &mdash;
                Plantilla: <strong>{{ $template->nombre }}</strong> &mdash;
                Total: <strong>{{ $diplomas->count() }}</strong> diploma(s)
            </div>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Folio</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Alumno</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Estado</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Emisión</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diplomas as $diploma)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-mono text-xs">{{ $diploma->folio }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $diploma->alumno->full_name ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                            {{ ucfirst($diploma->estado) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2">{{ $diploma->fecha_emision?->format('d/m/Y') }}</td>
                    <td class="border border-gray-200 px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.diplomas.mass.download', $diploma) }}"
                           class="text-blue-600 hover:underline">PDF</a>
                        <a href="{{ route('verificar', $diploma->token_qr) }}" target="_blank"
                           class="text-gray-500 hover:underline">Verificar</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No se encontraron diplomas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
