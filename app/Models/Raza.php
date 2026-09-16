<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raza extends Model
{
    protected $table = 'raza';

    protected $primaryKey = 'id_raza';

    public $timestamps = false;

    protected $fillable = [
        'nombre_raza',
        'especie',
    ];

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'id_raza', 'id_raza');
    }
}
