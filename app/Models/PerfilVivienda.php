<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilVivienda extends Model
{
    protected $table = 'perfil_vivienda';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'tipo_inmueble',
        'tenencia',
        'area_m2',
        'tiene_patio',
        'area_cubierta',
        'lugar_mascota',
        'personas_hogar',
        'con_quien_vive',
        'hay_ninos',
        'persona_con_alergia',
        'detalle_alergia',
        'todos_aceptan',
    ];

    protected function casts(): array
    {
        return [
            'tiene_patio' => 'boolean',
            'area_cubierta' => 'boolean',
            'hay_ninos' => 'boolean',
            'persona_con_alergia' => 'boolean',
            'todos_aceptan' => 'boolean',
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
