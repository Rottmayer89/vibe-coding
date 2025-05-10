@props(['sortable' => false, 'align' => 'left'])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-3 ' . $alignmentClasses[$align] . ' text-xs font-medium text-gray-500 uppercase tracking-wider']) }}>
    {{ $slot }}
</th>
