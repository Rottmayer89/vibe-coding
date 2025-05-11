@props(['categories', 'selectedCategoryId' => null])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <form action="{{ route('costs.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
        <div class="w-full sm:w-auto">
            <select 
                name="category_id" 
                class="w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                onchange="this.form.submit()"
            >
                <option value="">{{ __('All Categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($selectedCategoryId)
            <a href="{{ route('costs.index') }}" class="px-3 py-2 bg-gray-200 rounded-md text-sm text-gray-700 hover:bg-gray-300">
                {{ __('Reset') }}
            </a>
        @endif
    </form>
</div>
