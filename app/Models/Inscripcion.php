<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'id_user',
        'id_evento',
        'id_parqueadero',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function parqueadero()
    {
        return $this->belongsTo(Parqueadero::class, 'id_parqueadero');
    }
}
