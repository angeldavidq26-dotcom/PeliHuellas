<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeguimientoAdopcion extends Model
{
    protected $table = 'seguimiento_adopcion';

    public $timestamps = false;

    public const ESTADOS_ANIMAL = ['excelente', 'bueno', 'regular', 'preocupante'];

    protected $fillable = [
        'id_adopcion',
        'id_usuario_responsable',
        'fecha_seguimiento',
        'estado_animal',
        'observaciones',
        'fotos',
    ];

    protected function casts(): array
    {
        return [
            'fecha_seguimiento' => 'datetime',
            'fotos' => 'array',
        ];
    }

    public function adopcion(): BelongsTo
    {
        return $this->belongsTo(Adopcion::class, 'id_adopcion');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_responsable', 'id_usuario');
    }
}
