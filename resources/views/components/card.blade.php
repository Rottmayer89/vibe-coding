@props(['title' => null, 'footer' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if ($title)
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
    @endif

    <div class="p-6 bg-white">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-slate-500">
            {{ $footer }}
        </div>
    @endif
</div>
