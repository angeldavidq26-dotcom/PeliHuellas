<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferenciaPersonal extends Model
{
    protected $table = 'referencia_personal';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'parentesco',
        'telefono',
        'ocupacion',
    ];

    /**
     * id_usuario en esta tabla apunta a perfil_adoptante.id_usuario.
     */
    public function perfilAdoptante(): BelongsTo
    {
        return $this->belongsTo(PerfilAdoptante::class, 'id_usuario', 'id_usuario');
    }
}
