<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_tipo_rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tipoRol(): BelongsTo
    {
        return $this->belongsTo(TipoRol::class, 'id_tipo_rol');
    }

    public function isAdmin(): bool
    {
        return $this->id_tipo_rol === TipoRol::ADMIN_ID;
    }

    public function isUser(): bool
    {
        return $this->id_tipo_rol === TipoRol::USER_ID;
    }

    public function dashboardRoute(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'panel.dashboard';
    }
}
