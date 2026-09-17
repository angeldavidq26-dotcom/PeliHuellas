<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Auditoria extends Model
{
    protected $table = 'auditoria';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'auditable_type',
        'auditable_id',
        'datos',
        'ip',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'datos' => 'array',
            'fecha' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function registrar(string $accion, ?Model $auditable = null, ?int $idUsuario = null, array $datos = []): self
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'accion' => $accion,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'datos' => $datos !== [] ? $datos : null,
            'ip' => request()->ip(),
            'fecha' => now(),
        ]);
    }
}
