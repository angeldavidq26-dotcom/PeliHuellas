<?php

use App\Models\Adopcion;
use App\Models\TeamInvitation;
use App\Notifications\RecordatorioSeguimientoAdopcion;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    TeamInvitation::query()
        ->whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->delete();
})->daily()->description('Delete expired team invitations');

Schedule::call(function () {
    Adopcion::query()
        ->whereNotNull('proximo_seguimiento_at')
        ->where('proximo_seguimiento_at', '<=', now())
        ->with('solicitud.mascota.fundacion.usuario.user')
        ->get()
        ->each(function (Adopcion $adopcion) {
            $adopcion->solicitud?->mascota?->fundacion?->usuario?->user
                ?->notify(new RecordatorioSeguimientoAdopcion($adopcion));

            // Si la fundación ignora el recordatorio, lo reintenta en una
            // semana en vez de notificar todos los días.
            $adopcion->update(['proximo_seguimiento_at' => now()->addDays(7)]);
        });
})->daily()->description('Recordar a las fundaciones el seguimiento post-adopción pendiente');
