<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudAdopcion extends Model
{
    protected $table = 'solicitud_adopcion';

    protected $primaryKey = 'id_solicitud';

    public $timestamps = false;

    public const ESTADOS = [
        'pendiente',
        'aprobada',
        'en_pausa',
        'rechazada',
        'completada',
        'no_concretada',
        'cancelada',
    ];

    protected $fillable = [
        'id_usuario',
        'id_mascota',
        'fecha_solicitud',
        'estado',
        'mensaje_solicitante',
        'observaciones_fundacion',
        'fecha_resolucion',
        'id_usuario_responsable',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
            'fecha_resolucion' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }
}
