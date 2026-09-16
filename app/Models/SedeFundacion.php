<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SedeFundacion extends Model
{
    protected $table = 'sede_fundacion';

    protected $primaryKey = 'id_sede';

    public $timestamps = false;

    protected $fillable = [
        'id_fundacion',
        'nombre',
        'direccion',
        'ciudad',
        'telefono',
        'latitud',
        'longitud',
        'es_principal',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'es_principal' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function fundacion(): BelongsTo
    {
        return $this->belongsTo(Fundacion::class, 'id_fundacion', 'id_fundacion');
    }
}
