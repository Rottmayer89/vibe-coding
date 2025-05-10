@props(['isEven' => false])

<tr {{ $attributes->merge(['class' => $isEven ? 'bg-gray-50' : 'bg-white']) }}>
    {{ $slot }}
</tr>
