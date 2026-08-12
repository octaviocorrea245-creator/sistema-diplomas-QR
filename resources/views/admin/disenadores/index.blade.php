<x-app-layout>
    <x-slot name="header">
        <h2>Diseñadores</h2>
    </x-slot>

    <div style="background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,0.07); overflow:hidden;">

        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <h3 style="font-size:1rem; font-weight:600; color:#0D1B35; margin:0 0 0.15rem;">Lista de Diseñadores</h3>
                <p style="font-size:0.8rem; color:#64748b; margin:0;">Usuarios de tu departamento que pueden diseñar diplomas</p>
            </div>
            <a href="{{ route('admin.disenadores.create') }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#1A56B0; color:#fff; padding:0.5rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:500; text-decoration:none; transition:background 0.15s;"
               onmouseover="this.style.background='#1547A0'" onmouseout="this.style.background='#1A56B0'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nuevo Diseñador
            </a>
        </div>

        @if(session('success'))
        <div style="margin:1rem 1.5rem 0; padding:0.75rem 1rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:8px; color:#166534; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div style="overflow-x:auto; padding-bottom:0.5rem;">
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr>
                        <th style="background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1.5rem; border-bottom:2px solid #E2E8F0; text-align:left;">Nombre</th>
                        <th style="background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left;">Usuario</th>
                        <th style="background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left;">Departamento</th>
                        <th style="background:#F8FAFC; color:#475569; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; padding:0.75rem 1rem; border-bottom:2px solid #E2E8F0; text-align:left; width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disenadores as $d)
                    <tr>
                        <td style="padding:0.875rem 1.5rem; border-bottom:1px solid #F1F5F9; color:#1e293b;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:#EEF3FB; color:#1A56B0; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                    {{ strtoupper(substr($d->full_name, 0, 1)) }}
                                </div>
                                <span style="font-weight:500;">{{ $d->full_name }}</span>
                            </div>
                        </td>
                        <td style="padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9; color:#64748b; font-family:monospace; font-size:0.82rem;">{{ $d->username }}</td>
                        <td style="padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9; color:#64748b; font-size:0.82rem;">{{ $d->department->name ?? '—' }}</td>
                        <td style="padding:0.875rem 1rem; border-bottom:1px solid #F1F5F9;">
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.disenadores.edit', $d) }}"
                                   style="display:inline-flex; align-items:center; gap:4px; padding:0.35rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; text-decoration:none; border:1.5px solid #1A56B0; color:#1A56B0; transition:all 0.15s;">
                                    Editar
                                </a>
                                <form action="{{ route('admin.disenadores.destroy', $d) }}" method="POST" style="margin:0;"
                                      onsubmit="return confirmAction(event, '¿Eliminar a {{ $d->full_name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:0.35rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:500; cursor:pointer; border:1px solid rgba(192,57,43,0.25); color:#C0392B; background:transparent;">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:3rem; color:#94a3b8;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;margin:0 auto 0.75rem;display:block;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            No hay diseñadores registrados en tu departamento.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
