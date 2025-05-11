@props(['title', 'value', 'icon' => null, 'color' => 'indigo', 'change' => null, 'isPositiveChange' => true])

@php
    $colors = [
        'indigo' => [
            'bg' => 'bg-indigo-500',
            'text' => 'text-indigo-600',
            'light_bg' => 'bg-indigo-100',
            'border' => 'border-indigo-200',
        ],
        'green' => [
            'bg' => 'bg-green-500',
            'text' => 'text-green-600',
            'light_bg' => 'bg-green-100',
            'border' => 'border-green-200',
        ],
        'blue' => [
            'bg' => 'bg-blue-500',
            'text' => 'text-blue-600',
            'light_bg' => 'bg-blue-100',
            'border' => 'border-blue-200',
        ],
        'purple' => [
            'bg' => 'bg-purple-500',
            'text' => 'text-purple-600',
            'light_bg' => 'bg-purple-100',
            'border' => 'border-purple-200',
        ],
    ];
@endphp

<div class="bg-white rounded-lg shadow-md border {{ $colors[$color]['border'] }} overflow-hidden">
    <div class="px-5 py-12">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="rounded-full p-3 {{ $colors[$color]['light_bg'] }}">
                    {{ $icon }}
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ $title }}
                    </dt>
                    <dd>
                        <div class="text-lg font-medium {{ $colors[$color]['text'] }}">
                            {{ $value }}
                        </div>
                    </dd>
                    @if ($change)
                        <dd class="flex items-center text-sm">
                            <span class="{{ $isPositiveChange ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $isPositiveChange ? '+' : '-' }} {{ $change }}
                            </span>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
