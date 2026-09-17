<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adopcion extends Model
{
    protected $table = 'adopcion';

    public $timestamps = false;

    protected $fillable = [
        'id_solicitud',
        'fecha_entrega',
        'acta_url',
        'observaciones',
        'proximo_seguimiento_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'date',
            'proximo_seguimiento_at' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAdopcion::class, 'id_solicitud', 'id_solicitud');
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(SeguimientoAdopcion::class, 'id_adopcion')->orderByDesc('fecha_seguimiento');
    }
}
