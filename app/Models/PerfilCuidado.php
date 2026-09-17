<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilCuidado extends Model
{
    protected $table = 'perfil_cuidado';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'horas_solo_al_dia',
        'lugar_permanente',
        'donde_hace_necesidades',
        'tipo_alimento',
        'asume_costo_salud',
        'acepta_tratamiento',
        'acepta_esterilizacion',
        'acepta_cirugias',
        'tiene_otras_mascotas',
        'cuales_mascotas',
    ];

    protected function casts(): array
    {
        return [
            'asume_costo_salud' => 'boolean',
            'acepta_tratamiento' => 'boolean',
            'acepta_esterilizacion' => 'boolean',
            'acepta_cirugias' => 'boolean',
            'tiene_otras_mascotas' => 'boolean',
        ];
    }

    /**
     * id_usuario en esta tabla apunta a perfil_adoptante.id_usuario
     * (relación 1 a 1 con el perfil, no directamente con usuario).
     */
    public function perfilAdoptante(): BelongsTo
    {
        return $this->belongsTo(PerfilAdoptante::class, 'id_usuario', 'id_usuario');
    }
}
