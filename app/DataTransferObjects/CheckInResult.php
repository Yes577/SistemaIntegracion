<?php

namespace App\DataTransferObjects;

use App\Models\Inscripcion;

class CheckInResult
{
    public function __construct(
        public readonly bool $successful,
        public readonly string $message,
        public readonly ?Inscripcion $inscripcion = null,
        public readonly int $statusCode = 200,
        public readonly ?string $reason = null,
    ) {
    }

    public static function success(string $message, Inscripcion $inscripcion): self
    {
        return new self(true, $message, $inscripcion);
    }

    public static function error(string $message, int $statusCode = 422, ?string $reason = null): self
    {
        return new self(false, $message, null, $statusCode, $reason);
    }
}
