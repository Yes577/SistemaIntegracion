# Modulo de Notificaciones, QR y Check-in

## Resumen

El sistema reutiliza la arquitectura Laravel existente y agrega un modulo completo para:

- correos transaccionales por SMTP
- codigos QR por inscripcion
- check-in por escaneo QR
- procesamiento asincrono con colas
- recordatorios automáticos y manuales
- reportes operativos por evento

No se creo un proyecto nuevo ni una arquitectura paralela. Todo vive sobre el dominio actual de `Evento` e `Inscripcion`.

## Arquitectura implementada

### Capas nuevas

- `app/Services`
  - `QrTokenService`: firma y valida tokens QR, sincroniza expiracion.
  - `QrCodeService`: genera SVG de QR con `bacon/bacon-qr-code`.
  - `CheckInService`: valida token, evita reutilizacion y registra asistencia.
  - `EventReminderService`: localiza inscripciones candidatas y encola recordatorios.
  - `EventReportService`: consolida metricas del evento.
- `app/Events`
  - `InscripcionRegistrada`
  - `EventoDatosActualizados`
  - `CheckInRegistrado`
- `app/Listeners`
  - encolan jobs de correo a partir de los eventos de dominio.
- `app/Jobs`
  - `SendInscripcionConfirmationEmailJob`
  - `SendEventReminderEmailJob`
  - `SendEventUpdatedEmailJob`
  - `SendCheckInConfirmationEmailJob`
- `app/Mail`
  - mailables en espanol para confirmacion, recordatorio, cambios y check-in.
- `app/Http/Controllers/Admin`
  - vistas de reporte, escaner y disparo manual de recordatorios.

### Tablas y campos

Se extendio `inscripciones` con:

- `qr_uuid`
- `qr_emitido_at`
- `qr_expira_at`
- `estado_check_in`
- `check_in_at`
- `recordatorio_enviado_at`

Esto mantiene compatibilidad con el modelo actual sin abrir tablas accesorias innecesarias.

## Flujo de correos

### 1. Confirmacion de inscripcion

1. El usuario se inscribe en `InscripcionController`.
2. Se crea la inscripcion y se calcula vigencia QR.
3. Se dispara `InscripcionRegistrada`.
4. El listener encola `SendInscripcionConfirmationEmailJob`.
5. El job genera QR SVG y envia `InscripcionConfirmadaMail`.

### 2. Recordatorio automatico

1. `routes/console.php` programa `eventos:enviar-recordatorios` cada cinco minutos.
2. `EventReminderService` busca inscripciones a menos de 24 horas del evento.
3. Cada recordatorio se encola por job.
4. El job envia correo y marca `recordatorio_enviado_at`.

### 3. Notificacion de cambios

1. `EventoController@update` compara `fecha`, `hora` y `lugar`.
2. Si hubo cambios, dispara `EventoDatosActualizados`.
3. El listener encola un job por inscrito.
4. El correo incluye resumen de cambios y QR actualizado.

### 4. Confirmacion de check-in

1. El escaner valida el QR en `CheckInService`.
2. Si el check-in es exitoso, se dispara `CheckInRegistrado`.
3. El listener encola `SendCheckInConfirmationEmailJob`.

## Flujo de QR

### Token

Cada QR se basa en un token firmado con:

- `qr_uuid` unico por inscripcion
- `id_evento`
- timestamp de expiracion

Formato:

```text
base64url(payload).firma_hmac_sha256
```

La firma usa `APP_KEY`, por lo que el token no es manipulable sin invalidarse.

### Propiedades de seguridad

- No predecible: el UUID es aleatorio.
- Firmado: el payload esta protegido por HMAC.
- No reutilizable: `estado_check_in` y `check_in_at` bloquean un segundo uso.
- Expirable: `qr_expira_at` invalida el uso fuera de ventana.
- Acotado por evento: el backend rechaza QR usados sobre otro evento.

## Flujo de check-in

### Vista de escaneo

Ruta:

```text
/admin/eventos/{evento}/check-in
```

Soporta:

- webcam en navegador
- moviles con camara
- validacion manual del token

La UI usa `html5-qrcode` desde CDN para el reconocimiento en cliente. El backend nunca confia en el cliente: toda validacion real ocurre en `CheckInService`.

### Casos invalidados

Mensajes implementados en espanol para:

- QR reutilizado
- QR expirado
- QR de otro evento
- QR inexistente
- QR alterado o invalido

## Reportes

Ruta:

```text
/admin/eventos/{evento}/reporte
```

Metricas incluidas:

- inscritos totales
- asistentes confirmados
- porcentaje de asistencia
- detalle por usuario y estado de check-in

## SMTP y variables de entorno

No hay credenciales hardcodeadas. Todo sale de `.env`.

### Desarrollo con Mailtrap

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
```

### Produccion con SMTP estandar

Compatible con:

- Mailtrap
- Brevo SMTP
- Resend SMTP
- Gmail SMTP

Solo debes cambiar host, puerto, usuario, password y cifrado.

## Colas y workers

### Configuracion

```env
QUEUE_CONNECTION=database
EVENT_NOTIFICATION_QUEUE=mail
```

### Comandos

```bash
php artisan queue:work --queue=mail,default
php artisan schedule:work
php artisan eventos:enviar-recordatorios
```

### Cron sugerido en produccion

```bash
* * * * * php /ruta/al/proyecto/artisan schedule:run >> /dev/null 2>&1
```

## Como probar el sistema

### Flujo funcional

1. Crea un evento publicado.
2. Inscribe un usuario.
3. Verifica que se encole y envie el correo de confirmacion.
4. Abre el QR del usuario desde la vista del evento o desde el correo.
5. Entra al escaner del administrador.
6. Escanea el QR.
7. Comprueba el cambio de estado y el correo de asistencia.

### Tests automatizados

```bash
php artisan test --filter=EventOperationsModuleTest
```

Cobertura agregada:

- dispatch del correo de inscripcion
- envio real del mailable de confirmacion
- generacion del token QR
- render SVG del QR
- check-in exitoso
- rechazo por reutilizacion
- rechazo por evento incorrecto
- rechazo por expiracion
- encolado de recordatorios automaticos

## Compatibilidad con CI/CD

El modulo se mantuvo compatible con CI porque:

- usa `QUEUE_CONNECTION=sync` en testing por `phpunit.xml`
- no ejecuta correo dentro del request HTTP
- usa migraciones incrementales
- no hardcodea secretos
- no modifica pipelines existentes

### Pasos sugeridos para despliegue

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci
npm run build
php artisan queue:restart
```

Adicionalmente, el entorno debe tener:

- un worker de colas activo
- scheduler activo
- variables SMTP configuradas

## Archivos clave

- `config/eventos.php`
- `app/Services/QrTokenService.php`
- `app/Services/CheckInService.php`
- `app/Jobs/*`
- `resources/views/admin/eventos/checkin.blade.php`
- `resources/views/admin/eventos/report.blade.php`
- `tests/Feature/EventOperationsModuleTest.php`
