<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mascota extends Model
{
    protected $table = 'mascota';

    protected $primaryKey = 'id_mascota';

    public $timestamps = false;

    public const TAMANOS = ['pequeno', 'mediano', 'grande', 'gigante'];

    protected $fillable = [
        'id_fundacion',
        'id_sede',
        'id_raza',
        'nombre',
        'especie',
        'sexo',
        'tamano',
        'edad_aprox_meses',
        'peso_kg',
        'descripcion',
        'esterilizado',
        'vacunado',
        'fecha_ingreso',
        'estado',
        'fecha_retiro',
    ];

    protected function casts(): array
    {
        return [
            'esterilizado' => 'boolean',
            'vacunado' => 'boolean',
            'fecha_ingreso' => 'date',
            'fecha_retiro' => 'datetime',
        ];
    }

    public function fundacion(): BelongsTo
    {
        return $this->belongsTo(Fundacion::class, 'id_fundacion', 'id_fundacion');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(SedeFundacion::class, 'id_sede', 'id_sede');
    }

    public function raza(): BelongsTo
    {
        return $this->belongsTo(Raza::class, 'id_raza', 'id_raza');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoMascota::class, 'id_mascota', 'id_mascota')->orderBy('orden');
    }

    public function fotoPrincipal(): HasOne
    {
        return $this->hasOne(FotoMascota::class, 'id_mascota', 'id_mascota')
            ->where('es_principal', true);
    }

    public function caracteristicas(): BelongsToMany
    {
        return $this->belongsToMany(
            Caracteristica::class,
            'mascota_caracteristica',
            'id_mascota',
            'id_caracteristica'
        );
    }

    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->where('estado', 'disponible');
    }

    public function edadTexto(): string
    {
        if (! $this->edad_aprox_meses) {
            return '';
        }

        $anos = intdiv($this->edad_aprox_meses, 12);
        $meses = $this->edad_aprox_meses % 12;

        if ($anos >= 1) {
            return $anos === 1 ? '1 año' : "{$anos} años";
        }

        return $meses === 1 ? '1 mes' : "{$meses} meses";
    }

    public function tamanoTexto(): string
    {
        return match ($this->tamano) {
            'pequeno' => 'Pequeño',
            'mediano' => 'Mediano',
            'grande' => 'Grande',
            'gigante' => 'Gigante',
            default => ucfirst((string) $this->tamano),
        };
    }
}
