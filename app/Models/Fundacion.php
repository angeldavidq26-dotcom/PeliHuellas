<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'documento_certificado_url',
        'documento_representante_url',
        'documentos_enviados_at',
        'capacidad',
        'estado_verificacion',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'datetime',
            'documentos_enviados_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function sedes(): HasMany
    {
        return $this->hasMany(SedeFundacion::class, 'id_fundacion', 'id_fundacion');
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'id_fundacion', 'id_fundacion');
    }

    public function documentosCompletos(): bool
    {
        return filled($this->documento_certificado_url) && filled($this->documento_representante_url);
    }
}
