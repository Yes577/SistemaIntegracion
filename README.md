# SistemaIntegracion

Sistema de gestion de eventos desarrollado con Laravel 12, Blade, Tailwind CSS y PostgreSQL.

## Stack

- Backend: Laravel 12
- Frontend: Blade Templates + Tailwind CSS
- Base de datos: PostgreSQL
- Autenticacion: Laravel Breeze
- Colas: Laravel Queue con driver `database`
- Correo: SMTP estandar
- Build tool: Vite

## Modulos principales

- Gestion de eventos y estados
- Inscripciones y cupos
- Parqueaderos asociados
- Notificaciones transaccionales por correo
- Generacion de codigos QR por inscripcion
- Check-in QR con validacion y proteccion contra reutilizacion
- Reporte operativo por evento

## Configuracion rapida

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

## Variables de entorno importantes

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_integracion
DB_USERNAME=postgres
DB_PASSWORD=

QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

EVENT_NOTIFICATION_QUEUE=mail
EVENT_REMINDER_HOURS=24
EVENT_QR_TTL_HOURS=24
```

## Operacion del modulo QR y correo

```bash
php artisan queue:work --queue=mail,default
php artisan schedule:work
php artisan eventos:enviar-recordatorios
```

Tambien existe un envio manual de recordatorios desde el reporte del evento en el panel administrador.

## Testing

```bash
php artisan test
```

La suite incluye pruebas para:

- envio de correos
- generacion de QR
- validacion QR
- check-in
- bloqueo de reutilizacion

## Documentacion tecnica

La guia completa del modulo esta en [docs/modulos/notificaciones-qr-checkin.md](docs/modulos/notificaciones-qr-checkin.md).

## Bootstrap de administrador

Antes de ejecutar los seeders en entornos nuevos, define:

```env
ADMIN_BOOTSTRAP_NAME="Administrador"
ADMIN_BOOTSTRAP_EMAIL=
ADMIN_BOOTSTRAP_PASSWORD=
```

## Licencia

MIT
