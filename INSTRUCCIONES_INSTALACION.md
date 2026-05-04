# Sistema de Gestion de Eventos - Instrucciones de Instalacion

## Estado actual

- Laravel 12 instalado
- Modelos creados
- Controladores implementados
- Policies configuradas
- Vistas Blade creadas
- Rutas configuradas

## Pasos de instalacion

### 1. Crear archivo `.env`

```bash
cp .env.example .env
```

### 2. Generar APP_KEY

```bash
php artisan key:generate
```

### 3. Configurar base de datos

Define en `.env` las variables de PostgreSQL o la base de datos que vayas a usar.

### 4. Definir bootstrap del administrador

Antes de ejecutar seeders, agrega:

```env
ADMIN_BOOTSTRAP_NAME="Administrador"
ADMIN_BOOTSTRAP_EMAIL=admin@example.com
ADMIN_BOOTSTRAP_PASSWORD=DefineUnaClaveSeguraAqui
```

Si `ADMIN_BOOTSTRAP_EMAIL` o `ADMIN_BOOTSTRAP_PASSWORD` no estan definidas, el seeder no crea el administrador.

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. Ejecutar seeders

```bash
php artisan db:seed
```

### 7. Compilar assets

```bash
npm run dev
```

Para produccion:

```bash
npm run build
```

## Estructura creada

### Modelos

- `App\Models\Evento`
- `App\Models\EstadoEvento`
- `App\Models\Parqueadero`

### Controladores

- `EventoController`
- `DashboardController`

### Policies

- `EventoPolicy`

### Vistas

- `layouts/app.blade.php`
- `dashboard.blade.php`
- `eventos/index.blade.php`
- `eventos/create.blade.php`
- `eventos/edit.blade.php`
- `eventos/show.blade.php`

### Seeders

- `EstadoEventoSeeder`
- `DatabaseSeeder`

## Control de roles

- Admin: `id_tipo_rol = 1`
- Usuario normal: `id_tipo_rol = 2`

## Datos iniciales

Los seeders crean:

1. Estados de evento: borrador, publicado, cerrado y cancelado
2. Usuario administrador solo si las variables `ADMIN_BOOTSTRAP_EMAIL` y `ADMIN_BOOTSTRAP_PASSWORD` existen

## Iniciar servidor de desarrollo

```bash
php artisan serve
```

Luego accede a `http://localhost:8000`.

## Notas importantes

- No subas credenciales reales ni cuentas por defecto al repositorio.
- El middleware `admin` se configura en `bootstrap/app.php`.
- La autenticacion usa Laravel Breeze.
