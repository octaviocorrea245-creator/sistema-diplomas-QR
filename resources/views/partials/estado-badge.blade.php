{{-- resources/views/partials/estado-badge.blade.php --}}
{{-- Uso: @include('partials.estado-badge', ['estado' => $value]) --}}
@php
    $map = [
        'inscrito'   => ['label' => 'Inscrito',   'class' => 'bg-blue-100 text-blue-700'],
        'en_curso'   => ['label' => 'En curso',   'class' => 'bg-yellow-100 text-yellow-700'],
        'completado' => ['label' => 'Completado', 'class' => 'bg-green-100 text-green-700'],
        'baja'       => ['label' => 'Baja',       'class' => 'bg-red-100 text-red-700'],
    ];
    $info = $map[$estado] ?? ['label' => ucfirst($estado), 'class' => 'bg-gray-100 text-gray-600'];
@endphp
<span class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $info['class'] }}">
    {{ $info['label'] }}
</span>
