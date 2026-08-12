<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <h2 class="font-semibold text-xl text-gray-800">Notificaciones</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('supervisor.notificaciones.markAllRead') }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800 bg-white border border-blue-200 px-3 py-1.5 rounded-lg">
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @forelse($notifications as $notification)
                @php $data = $notification->data; @endphp
                <div class="flex items-start gap-4 px-5 py-4 {{ !$notification->read_at ? 'bg-blue-50/40 border-l-4 border-l-blue-500' : 'border-l-4 border-l-transparent' }} border-b border-gray-100 hover:bg-gray-50 transition">
                    <div class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-base
                        {{ $data['type'] === 'diploma_emitido' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $data['type'] === 'diploma_reemitido' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $data['type'] === 'curso_nuevo' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $data['type'] === 'curso_finalizado' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $data['type'] === 'curso_activo' ? 'bg-teal-100 text-teal-700' : '' }}
                    ">
                        @switch($data['type'])
                            @case('diploma_emitido')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @break
                            @case('diploma_reemitido')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                @break
                            @case('curso_nuevo')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                @break
                            @case('curso_finalizado')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @break
                            @case('curso_activo')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>
                                @break
                            @default
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        @endswitch
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 {{ !$notification->read_at ? 'font-semibold' : '' }}">{{ $data['message'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            @if(!empty($data['departamento_nombre']))
                                <span class="font-medium text-gray-500">{{ $data['departamento_nombre'] }}</span> &middot;
                            @endif
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        @if(isset($data['url']))
                            <a href="{{ $data['url'] }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">Ver</a>
                        @endif
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('supervisor.notificaciones.markRead', $notification->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600" title="Marcar como leída">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;margin:0 auto 0.75rem;color:#cbd5e1;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <p class="text-sm text-gray-400">No hay notificaciones</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
