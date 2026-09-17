@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl" class="text-[32px]! font-semibold! tracking-tight! lg:text-[40px]!">{{ $title }}</flux:heading>
    <flux:subheading class="mb-8! text-[17px]!">{{ $description }}</flux:subheading>
</div>
