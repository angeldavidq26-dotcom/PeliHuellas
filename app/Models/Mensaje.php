<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    protected $table = 'mensaje';

    public $timestamps = false;

    protected $fillable = [
        'id_solicitud',
        'id_usuario_remitente',
        'cuerpo',
        'fecha_envio',
        'leido_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_envio' => 'datetime',
            'leido_at' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAdopcion::class, 'id_solicitud', 'id_solicitud');
    }

    public function remitente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_remitente', 'id_usuario');
    }
}
