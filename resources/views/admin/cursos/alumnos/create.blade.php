{{-- resources/views/admin/cursos/alumnos/create.blade.php --}}
<x-app-layout>
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.cursos.index') }}" class="hover:text-indigo-600">Cursos</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.cursos.show', $curso) }}" class="hover:text-indigo-600">{{ $curso->nombre }}</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.cursos.alumnos.index', $curso) }}" class="hover:text-indigo-600">Alumnos</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Agregar</span>
    </nav>

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Agregar alumno al curso</h1>

    <form method="POST" action="{{ route('admin.cursos.alumnos.store', $curso) }}" x-data="{ modo: 'nuevo' }">
        @csrf

        {{-- Selector de modo --}}
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-2">¿Cómo quieres agregar al alumno?</p>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="modo" value="nuevo" x-model="modo"
                           {{ old('modo', 'nuevo') === 'nuevo' ? 'checked' : '' }}
                           class="text-indigo-600">
                    <span class="text-sm text-gray-700">Crear alumno nuevo</span>
                </label>
                @if($alumnosDisponibles->isNotEmpty())
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="modo" value="existente" x-model="modo"
                               {{ old('modo') === 'existente' ? 'checked' : '' }}
                               class="text-indigo-600">
                        <span class="text-sm text-gray-700">Inscribir alumno existente</span>
                    </label>
                @endif
            </div>
        </div>

        {{-- Formulario alumno nuevo --}}
        <div x-show="modo === 'nuevo'" x-cloak class="bg-white rounded-xl border border-gray-200 p-5 mb-5 space-y-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Datos del alumno</p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                       placeholder="Nombre y apellidos"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('full_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Username <span class="text-gray-400 font-normal">(también puede ser apodo)</span>
                </label>
                <input type="text" name="username" value="{{ old('username') }}"
                       placeholder="ej. jgarcia o elchaka"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña inicial</label>
                <input type="password" name="password"
                       placeholder="Mínimo 8 caracteres"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Selector alumno existente --}}
        <div x-show="modo === 'existente'" x-cloak class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-3">Alumno del departamento</p>

            @if($alumnosDisponibles->isEmpty())
                <p class="text-sm text-gray-400">Todos los alumnos del departamento ya están inscritos en este curso.</p>
            @else
                <label class="block text-sm font-medium text-gray-700 mb-1">Selecciona un alumno</label>
                <select name="user_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">— Elige un alumno —</option>
                    @foreach($alumnosDisponibles as $a)
                        <option value="{{ $a->id }}" {{ old('user_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->display_name }}
                            @if($a->full_name && $a->full_name !== $a->username)
                                ({{ $a->username }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Datos de inscripcion --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 space-y-4"
             x-data="{ estado: '{{ old('estado', 'inscrito') }}' }">
            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Inscripción</p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" x-model="estado"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="inscrito">Inscrito</option>
                    <option value="en_curso">En curso</option>
                    <option value="completado">Completado</option>
                    <option value="baja">Baja</option>
                </select>
                @error('estado')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="estado === 'completado'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de completado</label>
                <input type="date" name="fecha_completado" value="{{ old('fecha_completado') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('fecha_completado')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700 transition font-medium">
                Agregar alumno
            </button>
            <a href="{{ route('admin.cursos.alumnos.index', $curso) }}"
               class="px-5 py-2 rounded-lg text-sm border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
</x-app-layout>