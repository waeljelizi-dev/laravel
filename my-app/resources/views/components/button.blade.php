@props([
    'variant'=>'primary',
    'size' => 'md',
    'type'=> 'button',
    'href'=> null,
])

{{-- build the class list from props--}}
@php
    $base = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors';
    $variants = [
        'primary'=> 'bg-blue-600 text-white hover:bg-blue-700',
        'secondary'=> 'bg-gray-100 text-gray-800 hover:bg-gray-200',
        'danger' => 'bg-red-500 text-white hover:bg-red-600',
        'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100'
    ];

    $sizes = [
        'sm' => 'text-xs px-3 py-1.5',
        'md' => 'text-sm px-4 py-2',
        'lg' => 'text-base px-6 py-3' 
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) 
                     . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

{{-- Render <a> or <button> based on whether href is provided --}}

@if($href) 
    <a href="{{$href}}" {{ $attributes->merge(['class' => $classes]) }}>
        {{$slot}}
    </a>
@else
    <button type="{{$type}}" {{ $attributes->merge(['class' => $classes]) }}>
        {{$slot}}
    </button>
@endif
