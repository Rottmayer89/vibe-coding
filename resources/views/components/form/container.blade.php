@props(['action', 'method' => 'POST'])

<form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'GET' : 'POST' }}" {{ $attributes }}>
    @csrf
    
    @if (strtolower($method) !== 'get' && strtolower($method) !== 'post')
        @method($method)
    @endif
    
    {{ $slot }}
</form>
