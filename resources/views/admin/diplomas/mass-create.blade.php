<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Generación Masiva de Diplomas</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 rounded p-3 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.diplomas.mass.store') }}" class="bg-white shadow rounded-lg p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                <select name="curso_id" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar curso...</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" @selected(old('curso_id') == $curso->id)>
                            {{ $curso->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Solo se muestran cursos activos.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plantilla de Diploma</label>
                <select name="template_id" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar plantilla...</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" @selected(old('template_id') == $template->id)>
                            {{ $template->nombre }} ({{ $template->curso->nombre }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Debe tener elementos diseñados en el editor.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de emisión</label>
                <input type="date" name="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}" required
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded p-3 text-sm text-amber-800">
                <strong>Nota:</strong> Se generarán diplomas para todos los alumnos con estado
                <strong>"completado"</strong> en el curso seleccionado. Los alumnos que ya tengan
                un diploma para este curso serán omitidos.
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition font-medium"
                        onclick="return confirm('¿Generar diplomas para todos los alumnos completados?')">
                    Generar diplomas
                </button>
                <a href="{{ route('admin.diplomas.index') }}"
                   class="px-5 py-2 rounded border hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
