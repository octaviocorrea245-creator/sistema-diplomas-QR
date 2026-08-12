<x-app-layout>
    <x-slot name="header">
        <h2>Nuevo Departamento</h2>
    </x-slot>

    <div style="max-width:520px;">
        <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9;">
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Agregar Departamento</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">Completa los datos del nuevo departamento académico</p>
            </div>

            <form action="{{ route('supervisor.departamentos.store') }}" method="POST" style="padding:1.5rem;">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem;">
                        Nombre del Departamento <span style="color:#C0392B;">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
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

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem;">
                        Abreviatura <span style="color:#C0392B;">*</span>
                    </label>
                    <input type="text" name="abreviatura" id="abreviatura" value="{{ old('abreviatura') }}" required maxlength="20"
                           placeholder="Ej. ITI"
                           style="width:100%; padding:0.6rem 0.875rem; border:1.5px solid {{ $errors->has('abreviatura') ? '#C0392B' : '#D1D5DB' }};
                                  border-radius:8px; font-size:0.875rem; color:#1e293b; outline:none; transition:border-color 0.15s;
                                  font-family:inherit; box-sizing:border-box; text-transform:uppercase;"
                           onfocus="this.style.borderColor='#1A56B0'"
                           onblur="this.style.borderColor='{{ $errors->has('abreviatura') ? '#C0392B' : '#D1D5DB' }}'">
                    @error('abreviatura')
                        <p style="color:#C0392B; font-size:0.8rem; margin-top:0.35rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem;">
                        Descripción
                    </label>
                    <textarea name="descripccion" id="descripccion" rows="3" maxlength="500"
                              placeholder="Descripción breve del departamento académico"
                              style="width:100%; padding:0.6rem 0.875rem; border:1.5px solid {{ $errors->has('descripccion') ? '#C0392B' : '#D1D5DB' }};
                                     border-radius:8px; font-size:0.875rem; color:#1e293b; outline:none; transition:border-color 0.15s;
                                     font-family:inherit; box-sizing:border-box; resize:vertical; min-height:84px;"
                              onfocus="this.style.borderColor='#1A56B0'"
                              onblur="this.style.borderColor='{{ $errors->has('descripccion') ? '#C0392B' : '#D1D5DB' }}'">{{ old('descripccion') }}</textarea>
                    @error('descripccion')
                        <p style="color:#C0392B; font-size:0.8rem; margin-top:0.35rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.4rem;">
                        Color <span style="color:#9ca3af; font-weight:400;">(opcional)</span>
                    </label>
                    <input type="hidden" name="color" id="color" value="{{ old('color') ?? '#1A56B0' }}">
                    <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                        <input type="color" id="colorWheel" value="{{ old('color') ?? '#1A56B0' }}"
                               style="width:48px; height:38px; padding:0; border:1.5px solid #D1D5DB; border-radius:8px; background:#fff; cursor:pointer;">
                        <div id="colorPreview"
                             style="width:38px; height:38px; border-radius:8px; background:{{ old('color') ?? '#1A56B0' }}; border:1.5px solid #D1D5DB;"></div>
                        <input type="text" id="colorHex" value="{{ old('color') ?? '#1A56B0' }}" maxlength="7"
                               placeholder="#RRGGBB"
                               style="width:110px; padding:0.55rem 0.75rem; border:1.5px solid #D1D5DB; border-radius:8px; font-size:0.875rem;
                                      font-family:monospace; color:#1e293b; outline:none; transition:border-color 0.15s; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#1A56B0'"
                               onblur="this.style.borderColor='#D1D5DB'">
                    </div>
                    <div style="margin-top:0.6rem; display:flex; gap:0.4rem; flex-wrap:wrap;">
                        @php $swatches = ['#1A56B0','#F5A623','#7EC441','#E53935','#8E44AD','#00BCD4','#03A9F4','#0D1B35','#166534','#C0392B','#9ca3af','#000000']; @endphp
                        @foreach($swatches as $swatch)
                            <button type="button" class="swatch" data-color="{{ $swatch }}"
                                    style="width:26px; height:26px; border-radius:6px; background:{{ $swatch }}; border:2px solid #fff; box-shadow:0 0 0 1px #E2E8F0; cursor:pointer; padding:0;"
                                    onmouseover="this.style.boxShadow='0 0 0 2px {{ $swatch }}'"
                                    onmouseout="this.style.boxShadow='0 0 0 1px #E2E8F0'"></button>
                        @endforeach
                    </div>
                    @error('color')
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
                        Guardar Departamento
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

    <script>
        (function() {
            var hidden = document.getElementById('color');
            var wheel  = document.getElementById('colorWheel');
            var hex    = document.getElementById('colorHex');
            var prev   = document.getElementById('colorPreview');

            function isValidHex(v) {
                return /^#[0-9a-fA-F]{6}$/.test(v);
            }

            function apply(color) {
                hidden.value = color;
                hex.value    = color;
                wheel.value  = color;
                prev.style.background = color;
            }

            wheel.addEventListener('input', function() { apply(this.value); });

            hex.addEventListener('input', function() {
                var v = this.value.trim();
                if (isValidHex(v)) {
                    apply(v);
                    this.style.borderColor = '#D1D5DB';
                } else {
                    this.style.borderColor = '#C0392B';
                }
            });

            hex.addEventListener('blur', function() {
                var v = this.value.trim();
                if (!v) { apply(hidden.value); return; }
                if (!isValidHex(v)) { apply(hidden.value); this.style.borderColor = '#D1D5DB'; }
            });

            document.querySelectorAll('.swatch').forEach(function(btn) {
                btn.addEventListener('click', function() { apply(this.dataset.color); });
            });
        })();
    </script>

</x-app-layout>
