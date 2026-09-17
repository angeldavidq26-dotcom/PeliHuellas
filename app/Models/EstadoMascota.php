<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadoMascota extends Model
{
    protected $table = 'estado_mascota';

    public $timestamps = false;

    protected $fillable = [
        'id_mascota',
        'estado_anterior',
        'estado',
        'motivo',
        'fecha_cambio',
        'id_usuario_responsable',
    ];

    protected function casts(): array
    {
        return [
            'fecha_cambio' => 'datetime',
        ];
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }
}
