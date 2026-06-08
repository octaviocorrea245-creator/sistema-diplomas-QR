<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Crear Administrador</h2>
    </x-slot>

    <div class="py-8 max-w-md mx-auto">
        <form action="{{ route('supervisor.admins.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Usuario --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" name="username" value="{{ old('username') }}"
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                @error('username') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Departamento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                <select name="department_id"
                        class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    <option value="">-- Selecciona un departamento --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contraseña --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password"
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Crear Administrador
                </button>
                <a href="{{ route('supervisor.admins.index') }}"
                   class="px-6 py-2 border rounded hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>