<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmacion de inscripcion</title>
</head>
<body style="margin:0;background:#0a1222;font-family:Arial,sans-serif;color:#e5eef9;">
    <div style="max-width:680px;margin:0 auto;padding:32px 20px;">
        <div style="background:#101b33;border:1px solid rgba(103,232,249,.22);border-radius:24px;padding:32px;">
            <p style="margin:0 0 16px;color:#67e8f9;font-size:12px;letter-spacing:.24em;text-transform:uppercase;">Inscripcion confirmada</p>
            <h1 style="margin:0 0 16px;font-size:30px;line-height:1.2;color:#fff;">Tu cupo ya quedo reservado para {{ $inscripcion->evento->nombre }}</h1>
            <p style="margin:0 0 24px;line-height:1.7;color:#c7d5e7;">
                Hola {{ $inscripcion->user->name }}, este correo confirma tu inscripcion al evento.
            </p>

            <table role="presentation" width="100%" style="border-collapse:collapse;margin-bottom:24px;">
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);">Fecha</td>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $inscripcion->evento->fecha->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);">Hora</td>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $inscripcion->evento->hora->format('H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);">Lugar</td>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $inscripcion->evento->lugar }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;">Codigo de referencia</td>
                    <td style="padding:12px 0;text-align:right;color:#fff;">{{ $inscripcion->qr_uuid }}</td>
                </tr>
            </table>

            <div style="background:#0b1529;border:1px solid rgba(250,204,21,.18);border-radius:20px;padding:20px;text-align:center;">
                <p style="margin:0 0 16px;color:#fde68a;font-size:13px;">Presenta este QR el dia del evento. Tambien se adjunta como archivo PNG.</p>
                <div style="display:inline-block;background:#fff;padding:14px;border-radius:18px;">
                    <img src="{{ $message->embedData($qrPng, 'qr-inscripcion-'.$inscripcion->id.'.png', 'image/png') }}" alt="Codigo QR de inscripcion" style="display:block;width:240px;height:240px;">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
