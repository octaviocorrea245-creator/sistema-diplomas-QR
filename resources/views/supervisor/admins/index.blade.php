<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Administradores</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Lista de Administradores</h3>
            <a href="{{ route('supervisor.admins.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nuevo Admin
            </a>
        </div>

        <table class="w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Nombre</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Usuario</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Departamento</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2">{{ $admin->full_name }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $admin->username }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        {{ $admin->department->name ?? '—' }}
                    </td>
                    <td class="border border-gray-200 px-4 py-2">
                        <form action="{{ route('supervisor.admins.destroy', $admin) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este administrador?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No hay administradores aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>