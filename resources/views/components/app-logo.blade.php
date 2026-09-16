@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="config('app.name', 'PeliHuellas')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <img src="{{ asset('footprint_huella_logo.svg') }}" alt="" class="size-8">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('app.name', 'PeliHuellas')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <img src="{{ asset('footprint_huella_logo.svg') }}" alt="" class="size-8">
        </x-slot>
    </flux:brand>
@endif
