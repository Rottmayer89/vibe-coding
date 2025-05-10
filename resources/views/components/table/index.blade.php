@props(['striped' => true])

<div class="overflow-x-auto rounded-lg shadow">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        {{ $slot }}
    </table>
</div>
