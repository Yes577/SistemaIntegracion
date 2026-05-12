<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pendiente';
    public const STATUS_CONFIRMED = 'confirmado';
    public const STATUS_EXPIRED = 'expirado';

    protected $table = 'inscripciones';

    protected $fillable = [
        'id_user',
        'id_evento',
        'id_parqueadero',
        'qr_uuid',
        'qr_emitido_at',
        'qr_expira_at',
        'estado_check_in',
        'check_in_at',
        'recordatorio_enviado_at',
    ];

    protected $casts = [
        'qr_emitido_at' => 'datetime',
        'qr_expira_at' => 'datetime',
        'check_in_at' => 'datetime',
        'recordatorio_enviado_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function parqueadero(): BelongsTo
    {
        return $this->belongsTo(Parqueadero::class, 'id_parqueadero');
    }

    public function isQrExpired(): bool
    {
        return $this->qr_expira_at !== null && $this->qr_expira_at->isPast();
    }
}
