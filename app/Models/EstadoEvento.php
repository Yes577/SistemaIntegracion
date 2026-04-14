<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoEvento extends Model
{
    use HasFactory;

    protected $table = 'estados_evento';

    const BORRADOR = 'borrador';
    const PUBLICADO = 'publicado';
    const CERRADO = 'cerrado';
    const CANCELADO = 'cancelado';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'id_estado');
    }
}
