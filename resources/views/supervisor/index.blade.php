<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Panel del Supervisor</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <a href="{{ route('supervisor.admins.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm">Administradores</h3>
                <p class="text-xs text-gray-400 mt-1">Gestionar admins del departamento</p>
            </a>

            <a href="{{ route('supervisor.cursos.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm">Cursos</h3>
                <p class="text-xs text-gray-400 mt-1">Supervisar cursos del departamento</p>
            </a>

            <a href="{{ route('supervisor.alumnos.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm">Alumnos</h3>
                <p class="text-xs text-gray-400 mt-1">Lista de alumnos del departamento</p>
            </a>

            <a href="{{ route('supervisor.departamentos.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm">Departamentos</h3>
                <p class="text-xs text-gray-400 mt-1">Gestionar departamentos</p>
            </a>

            <a href="{{ route('supervisor.notificaciones.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-700 flex items-center justify-center mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm">
                    Notificaciones
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="ml-2 px-1.5 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </h3>
                <p class="text-xs text-gray-400 mt-1">Actividad reciente del departamento</p>
            </a>

        </div>

        {{-- Recent notifications --}}
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Actividad Reciente</h3>
                <a href="{{ route('supervisor.notificaciones.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">Ver todas &rarr;</a>
            </div>
            @php $recent = auth()->user()->notifications()->take(5)->get(); @endphp
            @if($recent->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recent as $n)
                        @php $d = $n->data; @endphp
                        <div class="flex items-center gap-3 px-5 py-3 {{ !$n->read_at ? 'bg-blue-50/40' : '' }}">
                            <div class="w-2 h-2 rounded-full {{ !$n->read_at ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                            <p class="text-sm text-gray-700 flex-1">
                                {{ $d['message'] ?? '' }}
                                @if(!empty($d['departamento_nombre']))
                                    <span class="text-xs text-gray-400 ml-1">({{ $d['departamento_nombre'] }})</span>
                                @endif
                            </p>
                            <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-sm text-gray-400">Sin actividad reciente</div>
            @endif
        </div>

    </div>
</x-app-layout>