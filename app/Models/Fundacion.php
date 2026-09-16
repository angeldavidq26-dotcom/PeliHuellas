<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fundacion extends Model
{
    protected $table = 'fundacion';

    protected $primaryKey = 'id_fundacion';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'nit',
        'correo',
        'telefono',
        'descripcion',
        'logo_url',
        'capacidad',
        'estado_verificacion',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'datetime',
        ];
    }

    public function sedes(): HasMany
    {
        return $this->hasMany(SedeFundacion::class, 'id_fundacion', 'id_fundacion');
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'id_fundacion', 'id_fundacion');
    }
}
