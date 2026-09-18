<x-admin-shell active="verificaciones">
    <livewire:revision-fundaciones :fundacion="request()->integer('fundacion') ?: null" />
</x-admin-shell>
