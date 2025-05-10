@props(['name', 'label', 'placeholder' => '', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error($name) border-red-500 @else border-gray-300 @enderror"
        @if ($required) required @endif
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        {{ $slot }}
    </select>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
