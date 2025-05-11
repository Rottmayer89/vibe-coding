@props(['class' => ''])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 {{ $class }}">
    {{ $slot }}
</div>
