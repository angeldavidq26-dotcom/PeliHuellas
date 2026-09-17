<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PerfilAdoptante extends Model
{
    protected $table = 'perfil_adoptante';

    protected $primaryKey = 'id_usuario';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'ocupacion',
        'desea_adoptar',
        'experiencia_previa',
        'decision_familiar',
        'todos_de_acuerdo',
        'motivacion',
        'completo',
        'fecha_diligenciamiento',
        'fecha_actualizacion',
    ];

    protected function casts(): array
    {
        return [
            'experiencia_previa' => 'boolean',
            'decision_familiar' => 'boolean',
            'todos_de_acuerdo' => 'boolean',
            'completo' => 'boolean',
            'fecha_diligenciamiento' => 'datetime',
            'fecha_actualizacion' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vivienda(): HasOne
    {
        return $this->hasOne(PerfilVivienda::class, 'id_usuario', 'id_usuario');
    }

    public function cuidado(): HasOne
    {
        return $this->hasOne(PerfilCuidado::class, 'id_usuario', 'id_usuario');
    }

    public function referencias(): HasMany
    {
        return $this->hasMany(ReferenciaPersonal::class, 'id_usuario', 'id_usuario');
    }
}
