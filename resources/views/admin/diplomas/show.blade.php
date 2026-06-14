<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Diploma {{ $diploma->folio }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.diplomas.index') }}"
                   class="px-3 py-1.5 rounded border text-sm hover:bg-gray-100">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Información del diploma</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-gray-400">Folio</dt>
                    <dd class="font-mono font-medium">{{ $diploma->folio }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Estado</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $diploma->estado === 'emitido' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $diploma->estado === 'revocado' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $diploma->estado === 'reemitido' ? 'bg-blue-100 text-blue-800' : '' }}
                        ">
                            {{ ucfirst($diploma->estado) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Fecha de emisión</dt>
                    <dd>{{ $diploma->fecha_emision->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Emitido por</dt>
                    <dd>{{ $diploma->emisor->full_name ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Alumno</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-gray-400">Nombre</dt>
                    <dd class="font-medium">{{ $diploma->alumno->full_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Usuario</dt>
                    <dd>{{ $diploma->alumno->username ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Curso</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-gray-400">Nombre</dt>
                    <dd class="font-medium">{{ $diploma->curso->nombre ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Horas</dt>
                    <dd>{{ $diploma->curso->horas ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Fecha inicio</dt>
                    <dd>{{ $diploma->curso->fecha_inicio ? $diploma->curso->fecha_inicio->format('d/m/Y') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Fecha fin</dt>
                    <dd>{{ $diploma->curso->fecha_fin ? $diploma->curso->fecha_fin->format('d/m/Y') : '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Token QR</h3>
            <p class="text-xs font-mono bg-gray-50 p-2 rounded">{{ $diploma->token_qr }}</p>
        </div>

        @if($diploma->reimpresiones->isNotEmpty())
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Reimpresiones</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="pb-1">Fecha</th>
                        <th class="pb-1">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diploma->reimpresiones as $r)
                    <tr class="border-t border-gray-50">
                        <td class="py-1">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-1">{{ $r->motivo ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
