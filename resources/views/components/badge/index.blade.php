@props([
    'color' => 'blue',
    'size' => 'md',
])

@php
    $colors = [
        'blue' => 'bg-blue-100 text-blue-800',
        'gray' => 'bg-gray-100 text-gray-800',
        'red' => 'bg-red-100 text-red-800',
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'pink' => 'bg-pink-100 text-pink-800',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];
    
    $baseClasses = 'inline-flex items-center rounded-full font-medium';
@endphp

<span {{ $attributes->merge(['class' => $baseClasses . ' ' . $colors[$color] . ' ' . $sizes[$size]]) }}>
    {{ $slot }}
</span>
