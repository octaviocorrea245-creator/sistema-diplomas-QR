<x-app-layout>
    <x-slot name="header">
        <h2>Departamentos</h2>
    </x-slot>

    @php
    $deptColor = function($abbr, $color = null) {
        if ($color) {
            $a = ltrim($color, '#');
            $rgb = [hexdec(substr($a,0,2)), hexdec(substr($a,2,2)), hexdec(substr($a,4,2))];
            $bg = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',0.08)';
            $light = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',0.2)';
            return ['accent' => $color, 'bg' => $bg, 'light' => $light];
        }
        $a = mb_strtoupper($abbr ?? '');
        if ($a === 'IAEV') return ['accent'=>'#F5A623','bg'=>'#FEF9EC','light'=>'#FFF3D1'];
        if ($a === 'IBIO') return ['accent'=>'#7EC441','bg'=>'#F1F9EA','light'=>'#DFFAC4'];
        if ($a === 'ITM' || $a === 'IMA') return ['accent'=>'#E53935','bg'=>'#FEECEB','light'=>'#FFCDD2'];
        if ($a === 'CIA')  return ['accent'=>'#8E44AD','bg'=>'#F5EEF8','light'=>'#E8D5F5'];
        if ($a === 'IDIA') return ['accent'=>'#00BCD4','bg'=>'#E0F7FA','light'=>'#B2EBF2'];
        if ($a === 'ITI')  return ['accent'=>'#03A9F4','bg'=>'#E1F5FE','light'=>'#B3E5FC'];
        return ['accent'=>'#64748B','bg'=>'#F1F5F9','light'=>'#E2E8F0'];
    };
    @endphp

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem;">
        <div>
            <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Carreras y Departamentos</h3>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">{{ count($departments) }} departamentos registrados</p>
        </div>
        <a href="{{ route('supervisor.departamentos.create') }}"
           style="display:inline-flex; align-items:center; gap:6px; background:#1A56B0; color:#fff; padding:0.5rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:500; text-decoration:none; transition:background 0.15s;"
           onmouseover="this.style.background='#1547A0'" onmouseout="this.style.background='#1A56B0'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nuevo Departamento
        </a>
    </div>

    @if(session('success'))
    <div style="margin-bottom:1.25rem; padding:0.75rem 1rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:8px; color:#166534; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Cards grid --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1rem;">

        @forelse($departments as $dept)
        @php $c = $deptColor($dept->abreviatura, $dept->color); @endphp

        <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden; border:1.5px solid #F1F5F9; transition:box-shadow 0.15s, transform 0.15s;"
             onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)'"
             onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.07)'; this.style.transform='none'">

            {{-- Color header --}}
            <div style="background:{{ $c['bg'] }}; padding:1.5rem 1.5rem 1rem; position:relative; border-bottom:3px solid {{ $c['accent'] }};">
                <div style="display:flex; align-items:flex-start; justify-content:space-between;">
                    {{-- Abbreviation badge --}}
                    <div style="background:{{ $c['accent'] }}; color:#fff; font-weight:800; font-size:1.1rem; letter-spacing:0.05em;
                                padding:0.4rem 0.85rem; border-radius:8px; display:inline-block; font-family:'Inter',sans-serif;">
                        {{ $dept->abreviatura }}
                    </div>
                    {{-- ID chip --}}
                    <span style="font-size:0.7rem; color:{{ $c['accent'] }}; background:{{ $c['light'] }}; padding:0.2rem 0.5rem; border-radius:20px; font-weight:600;">#{{ $dept->id }}</span>
                </div>
            </div>

            {{-- Body --}}
            <div style="padding:1rem 1.5rem 1.25rem;">
                <p style="font-size:0.9rem; font-weight:600; color:#0D1B35; margin:0 0 1rem; line-height:1.35;">{{ $dept->name }}</p>

                <div style="display:flex; gap:6px;">
                    <a href="{{ route('supervisor.departamentos.edit', $dept->id) }}"
                       style="display:inline-flex; align-items:center; gap:4px; padding:0.35rem 0.75rem; border-radius:6px;
                              font-size:0.8rem; font-weight:500; text-decoration:none; transition:all 0.15s;
                              border:1.5px solid {{ $c['accent'] }}; color:{{ $c['accent'] }}; background:transparent;"
                       onmouseover="this.style.background='{{ $c['bg'] }}'" onmouseout="this.style.background='transparent'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Editar
                    </a>
                    <form action="{{ route('supervisor.departamentos.destroy', $dept->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('¿Eliminar «{{ $dept->name }}»?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="display:inline-flex; align-items:center; gap:4px; padding:0.35rem 0.75rem; border-radius:6px;
                                       font-size:0.8rem; font-weight:500; cursor:pointer; transition:all 0.15s;
                                       border:1px solid rgba(192,57,43,0.25); color:#C0392B; background:transparent;"
                                onmouseover="this.style.background='rgba(192,57,43,0.08)'" onmouseout="this.style.background='transparent'">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div style="grid-column:1/-1; text-align:center; padding:3rem; background:#fff; border-radius:14px; color:#94a3b8;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
            No hay departamentos registrados.
        </div>
        @endforelse
    </div>

</x-app-layout>
