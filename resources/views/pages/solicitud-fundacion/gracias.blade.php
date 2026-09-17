<x-layouts::auth :title="__('Solicitud enviada')">
    <div class="flex flex-col items-center gap-6 text-center">
        <span class="flex size-14 items-center justify-center rounded-full bg-[#dcece4] text-[#1f5c47]">
            <flux:icon name="check-circle" variant="outline" class="size-8" />
        </span>

        <x-auth-header
            :title="__('¡Gracias por tu solicitud!')"
            :description="__('Nuestro equipo va a revisar la información de tu fundación. Te vamos a contactar por correo en los próximos días para confirmar tu ingreso a PeliHuellas.')"
        />

        <flux:button :href="route('home')" variant="primary" class="w-full" wire:navigate>
            {{ __('Volver al inicio') }}
        </flux:button>
    </div>
</x-layouts::auth>
