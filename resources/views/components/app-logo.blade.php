@props([
    'href' => null,
    'size' => 'md',
    'light' => false,
    'sidebar' => false,
])

@php
    $sizes = [
        'sm' => ['badge' => 'h-7 w-7', 'icon' => 'h-3.5 w-3.5', 'text' => 'text-lg'],
        'md' => ['badge' => 'h-8 w-8', 'icon' => 'h-4 w-4', 'text' => 'text-lg'],
        'lg' => ['badge' => 'h-9 w-9', 'icon' => 'h-5 w-5', 'text' => 'text-xl'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
    $textColor = $light ? 'text-white' : 'text-[#1B5C4F]';
@endphp

<a
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}
>
    <span class="flex {{ $s['badge'] }} shrink-0 items-center justify-center rounded-lg bg-[#1B5C4F]">
        <img src="{{ asset('logo-pelihuellas.svg') }}" alt="" class="{{ $s['icon'] }}">
    </span>
    <span class="pf-serif {{ $s['text'] }} font-bold {{ $textColor }}">PeliHuellas</span>
</a>
