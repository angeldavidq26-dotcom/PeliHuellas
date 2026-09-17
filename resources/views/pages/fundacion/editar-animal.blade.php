<x-fundacion-shell :fundacion="$fundacion" active="mis-animales">
    <livewire:editar-animal :fundacion="$fundacion" :mascota="$mascota" />
    <livewire:historial-medico-mascota :fundacion="$fundacion" :mascota="$mascota" />
</x-fundacion-shell>
