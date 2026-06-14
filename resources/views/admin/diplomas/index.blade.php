<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Diplomas</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Lista de Diplomas</h3>
            <a href="{{ route('admin.diplomas.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Emitir Diploma
            </a>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Folio</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Alumno</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Curso</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Estado</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Emisión</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diplomas as $diploma)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 font-mono font-medium text-xs">{{ $diploma->folio }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $diploma->alumno->full_name ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $diploma->curso->nombre ?? '—' }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $diploma->estado === 'emitido' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $diploma->estado === 'revocado' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $diploma->estado === 'reemitido' ? 'bg-blue-100 text-blue-800' : '' }}
                        ">
                            {{ ucfirst($diploma->estado) }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2">{{ $diploma->fecha_emision->format('d/m/Y') }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        <a href="{{ route('admin.diplomas.show', $diploma) }}" class="text-blue-500 hover:underline">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No hay diplomas emitidos aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $diplomas->links() }}
        </div>
    </div>
</x-app-layout>
