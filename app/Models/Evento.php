<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'hora',
        'lugar',
        'tiene_parqueadero',
        'capacidad_maxima',
        'capacidad_actual',
        'id_estado',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'tiene_parqueadero' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoEvento::class, 'id_estado');
    }

    public function parqueadero(): HasOne
    {
        return $this->hasOne(Parqueadero::class, 'id_evento');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'id_evento');
    }

    public function startsAt(): CarbonImmutable
    {
        return CarbonImmutable::parse(sprintf(
            '%s %s',
            $this->fecha->format('Y-m-d'),
            $this->hora->format('H:i:s'),
        ), config('app.timezone'));
    }
}
