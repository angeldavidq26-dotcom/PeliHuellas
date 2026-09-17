<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Model
{
    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tipo_documento',
        'numero_documento',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'telefono',
        'correo',
        'direccion',
        'descripcion',
        'foto_url',
        'red_social',
        'contrasena_hash',
        'fecha_registro',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_registro' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function perfilAdoptante(): HasOne
    {
        return $this->hasOne(PerfilAdoptante::class, 'id_usuario', 'id_usuario');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudAdopcion::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Busca el registro de dominio (usuario) enlazado a la cuenta de
     * autenticación indicada, si ya existe. Muchas partes del esquema
     * (solicitudes, perfil de adoptante, etc.) referencian
     * usuario.id_usuario, no users.id — este es el puente entre ambos.
     *
     * No lo crea automáticamente: usuario.numero_documento es único junto
     * con tipo_documento, así que el registro solo debe nacer una vez que
     * tenemos un número de documento real (primer paso del formulario de
     * perfil de adoptante), nunca con un valor de relleno.
     */
    public static function paraUser(User $user): ?self
    {
        return self::query()->where('id_user', $user->id)->first();
    }
}
