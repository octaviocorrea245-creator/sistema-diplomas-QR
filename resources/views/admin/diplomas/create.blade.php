<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Emitir Diploma</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 rounded p-3 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.diplomas.store') }}" method="POST" class="space-y-5" x-data="diplomaForm()">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Curso <span class="text-red-500">*</span></label>
                <select name="curso_id" x-model="cursoId" x-on:change="cargarDatos()" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                            {{ $curso->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Plantilla / Versión <span class="text-red-500">*</span></label>
                <select name="version_plantilla_id" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Primero selecciona un curso</option>
                    @foreach($plantillas as $plantilla)
                        <optgroup label="{{ $plantilla->nombre }}">
                            @foreach($plantilla->versiones as $version)
                                <option value="{{ $version->id }}"
                                    data-plantilla="{{ $plantilla->id }}"
                                    {{ old('version_plantilla_id') == $version->id ? 'selected' : '' }}>
                                    v{{ $version->version }} {{ $version->activa ? '(Activa)' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Alumno <span class="text-red-500">*</span></label>
                <select name="alumno_id" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Primero selecciona un curso</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Solo se muestran alumnos con estado "completado" en el curso.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Fecha de emisión <span class="text-red-500">*</span></label>
                <input type="date" name="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Emitir diploma
                </button>
                <a href="{{ route('admin.diplomas.index') }}"
                   class="px-5 py-2 rounded border hover:bg-gray-100 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function diplomaForm() {
            return {
                cursoId: '{{ old('curso_id') }}',
                cargarDatos() {
                    if (!this.cursoId) return;

                    fetch(`/admin/alumnos-por-curso/${this.cursoId}`)
                        .then(r => r.json())
                        .then(alumnos => {
                            const sel = document.querySelector('select[name="alumno_id"]');
                            sel.innerHTML = '<option value="">Seleccionar alumno</option>';
                            alumnos.forEach(a => {
                                sel.innerHTML += `<option value="${a.id}">${a.full_name} (${a.username})</option>`;
                            });
                        });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cursoSelect = document.querySelector('select[name="curso_id"]');
            if (cursoSelect.value) {
                const event = new Event('change');
                cursoSelect.dispatchEvent(event);
            }
        });
    </script>
    @endpush
</x-app-layout>
