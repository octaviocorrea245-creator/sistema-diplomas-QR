<x-app-layout>
    <x-slot name="header">
        <h2>Editar Departamento</h2>
    </x-slot>

    <div style="max-width:520px;">
        <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9;">
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Editar Departamento</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">Modifica el nombre del departamento</p>
            </div>

            <form action="{{ route('supervisor.departamentos.update', $departamento->id) }}" method="POST" style="padding:1.5rem;">
                @csrf
                @method('PUT')

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem;">
                        Nombre del Departamento <span style="color:#C0392B;">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $departamento->name) }}" required
                           placeholder="Ej. Ingeniería en Tecnologías de la Información"
                           style="width:100%; padding:0.6rem 0.875rem; border:1.5px solid {{ $errors->has('name') ? '#C0392B' : '#D1D5DB' }};
                                  border-radius:8px; font-size:0.875rem; color:#1e293b; outline:none; transition:border-color 0.15s;
                                  font-family:inherit; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#1A56B0'"
                           onblur="this.style.borderColor='{{ $errors->has('name') ? '#C0392B' : '#D1D5DB' }}'">
                    @error('name')
                        <p style="color:#C0392B; font-size:0.8rem; margin-top:0.35rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:6px; background:#1A56B0; color:#fff;
                                   padding:0.55rem 1.25rem; border-radius:8px; font-size:0.875rem; font-weight:500;
                                   border:none; cursor:pointer; transition:background 0.15s;"
                            onmouseover="this.style.background='#1547A0'"
                            onmouseout="this.style.background='#1A56B0'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('supervisor.departamentos.index') }}"
                       style="display:inline-flex; align-items:center; gap:6px; color:#64748b; padding:0.55rem 1.1rem;
                              border-radius:8px; font-size:0.875rem; font-weight:500; border:1.5px solid #E2E8F0;
                              text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.background='#F8FAFC'"
                       onmouseout="this.style.background='transparent'">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
