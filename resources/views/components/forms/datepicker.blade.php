@props([
    'name',
    'id' => null,
    'value' => '',
    'label' => '',
    'required' => false,
    'class' => '',
])

@php
    $id = $id ?? $name;
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div
        x-data="{ 
            value: '{{ $value }}',
            init() {
                this.$refs.input.value = this.value;
            }
        }"
        class="relative"
    >
        <input 
            x-ref="input"
            type="date" 
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ' . $class]) }}
        >
    </div>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
