<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Administrador
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('supervisor.admins.update', $admin) }}"
                      method="POST"
                      class="space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre completo
                        </label>
                        <input type="text"
                               name="full_name"
                               value="{{ old('full_name', $admin->full_name) }}"
                               placeholder="Ej. Juan Pérez"
                               class="w-full border-gray-300 rounded shadow-sm
                                      focus:ring-blue-500 focus:border-blue-500">
                        @error('full_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Usuario --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Usuario
                        </label>
                        <input type="text"
                               name="username"
                               value="{{ old('username', $admin->username) }}"
                               placeholder="Ej. jperez"
                               class="w-full border-gray-300 rounded shadow-sm
                                      focus:ring-blue-500 focus:border-blue-500">
                        @error('username')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Departamento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Departamento
                        </label>
                        <select name="department_id"
                                class="w-full border-gray-300 rounded shadow-sm
                                       focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Selecciona un departamento --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ old('department_id', $admin->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contraseña (opcional) --}}
                    <div class="border-t pt-4">
                        <p class="text-xs text-gray-400 mb-3">
                            Deja en blanco si no deseas cambiar la contraseña.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nueva contraseña
                                </label>
                                <input type="password"
                                       name="password"
                                       placeholder="Mínimo 8 caracteres"
                                       class="w-full border-gray-300 rounded shadow-sm
                                              focus:ring-blue-500 focus:border-blue-500">
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirmar nueva contraseña
                                </label>
                                <input type="password"
                                       name="password_confirmation"
                                       placeholder="Repite la nueva contraseña"
                                       class="w-full border-gray-300 rounded shadow-sm
                                              focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Guardar cambios
                        </button>
                        <a href="{{ route('supervisor.admins.index') }}"
                           class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-700">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>