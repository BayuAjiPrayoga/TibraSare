{{-- Dropdown Link sub-component --}}
@props([
    'method' => 'GET',
])

@if(strtoupper($method) === 'GET')
    <a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none']) }}>
        {{ $slot }}
    </a>
@else
    <form method="POST" action="{{ $attributes->get('href') }}">
        @csrf
        @if(!in_array(strtoupper($method), ['GET', 'POST']))
            @method($method)
        @endif
        <button type="submit" {{ $attributes->except('href')->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none']) }}>
            {{ $slot }}
        </button>
    </form>
@endif
