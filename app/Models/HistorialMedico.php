<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialMedico extends Model
{
    protected $table = 'historial_medico';

    public $timestamps = false;

    public const TIPOS = ['vacuna', 'desparasitacion', 'cirugia', 'consulta', 'otro'];

    protected $fillable = [
        'id_mascota',
        'tipo',
        'fecha',
        'veterinario',
        'documento_url',
        'observaciones',
        'id_usuario_responsable',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'fecha_registro' => 'datetime',
        ];
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_responsable', 'id_usuario');
    }
}
