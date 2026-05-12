<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambios en el evento</title>
</head>
<body style="margin:0;background:#0a1222;font-family:Arial,sans-serif;color:#e5eef9;">
    <div style="max-width:680px;margin:0 auto;padding:32px 20px;">
        <div style="background:#101b33;border:1px solid rgba(103,232,249,.22);border-radius:24px;padding:32px;">
            <p style="margin:0 0 16px;color:#67e8f9;font-size:12px;letter-spacing:.24em;text-transform:uppercase;">Actualizacion del evento</p>
            <h1 style="margin:0 0 16px;font-size:30px;line-height:1.2;color:#fff;">Hubo cambios en {{ $inscripcion->evento->nombre }}</h1>
            <p style="margin:0 0 24px;line-height:1.7;color:#c7d5e7;">
                El organizador actualizo informacion relevante. Este es el resumen de cambios y tu QR actualizado.
            </p>

            <table role="presentation" width="100%" style="border-collapse:collapse;margin-bottom:24px;">
                @foreach($cambios as $campo => $detalle)
                    <tr>
                        <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-transform:capitalize;">{{ $campo }}</td>
                        <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);color:#94a8c2;">{{ $detalle['anterior'] }}</td>
                        <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $detalle['nuevo'] }}</td>
                    </tr>
                @endforeach
            </table>

            <div style="background:#0b1529;border:1px solid rgba(250,204,21,.18);border-radius:20px;padding:20px;text-align:center;">
                <p style="margin:0 0 16px;color:#fde68a;font-size:13px;">Conserva este nuevo QR o usa el adjunto actualizado.</p>
                <div style="display:inline-block;background:#fff;padding:14px;border-radius:18px;">
                    <img src="{{ $message->embedData($qrPng, 'qr-actualizado-'.$inscripcion->id.'.png', 'image/png') }}" alt="Codigo QR actualizado" style="display:block;width:240px;height:240px;">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
