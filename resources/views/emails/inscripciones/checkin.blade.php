<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencia confirmada</title>
</head>
<body style="margin:0;background:#0a1222;font-family:Arial,sans-serif;color:#e5eef9;">
    <div style="max-width:680px;margin:0 auto;padding:32px 20px;">
        <div style="background:#101b33;border:1px solid rgba(52,211,153,.22);border-radius:24px;padding:32px;">
            <p style="margin:0 0 16px;color:#86efac;font-size:12px;letter-spacing:.24em;text-transform:uppercase;">Check-in confirmado</p>
            <h1 style="margin:0 0 16px;font-size:30px;line-height:1.2;color:#fff;">Tu asistencia ya fue registrada</h1>
            <p style="margin:0 0 24px;line-height:1.7;color:#c7d5e7;">
                Hola {{ $inscripcion->user->name }}, confirmamos tu ingreso al evento {{ $inscripcion->evento->nombre }}.
            </p>

            <table role="presentation" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);">Fecha del evento</td>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $inscripcion->evento->fecha->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);">Hora del check-in</td>
                    <td style="padding:12px 0;border-bottom:1px solid rgba(255,255,255,.08);text-align:right;color:#fff;">{{ $inscripcion->check_in_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;">Lugar</td>
                    <td style="padding:12px 0;text-align:right;color:#fff;">{{ $inscripcion->evento->lugar }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
