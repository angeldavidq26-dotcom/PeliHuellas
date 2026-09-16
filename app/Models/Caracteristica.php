<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Caracteristica extends Model
{
    protected $table = 'caracteristica';

    protected $primaryKey = 'id_caracteristica';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function mascotas(): BelongsToMany
    {
        return $this->belongsToMany(
            Mascota::class,
            'mascota_caracteristica',
            'id_caracteristica',
            'id_mascota'
        );
    }
}
