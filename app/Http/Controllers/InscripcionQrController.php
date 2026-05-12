<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Services\QrCodeService;
use App\Services\QrTokenService;
use Symfony\Component\HttpFoundation\Response;

class InscripcionQrController extends Controller
{
    public function show(Inscripcion $inscripcion, QrTokenService $qrTokenService, QrCodeService $qrCodeService): Response
    {
        $user = auth()->user();

        $canView = $inscripcion->id_user === $user->id
            || ($inscripcion->evento && $inscripcion->evento->id_usuario === $user->id);

        abort_unless($canView, 403, 'No tienes permiso para ver este codigo QR.');

        $token = $qrTokenService->generateToken($inscripcion->loadMissing('evento'));

        return response($qrCodeService->generateSvg($token), 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store, max-age=0',
        ]);
    }
}
