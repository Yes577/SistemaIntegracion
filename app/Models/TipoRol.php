<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoRol extends Model
{
    use HasFactory;

    public const ADMIN_ID = 1;
    public const USER_ID = 2;

    protected $table = 'tipo_rol';

    protected $fillable = [
        'nombre',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_tipo_rol');
    }
}
