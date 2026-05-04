# SistemaIntegracion

Sistema de gestion de eventos desarrollado con Laravel 12, Blade, Tailwind CSS y PostgreSQL.

## Stack

- Backend: Laravel 12
- Frontend: Blade Templates + Tailwind CSS
- Base de datos: PostgreSQL
- Autenticacion: Laravel Breeze
- Build tool: Vite

## Funcionalidades

### Gestion de eventos

- Crear eventos solo para administradores
- Editar eventos solo para administradores
- Eliminar eventos solo para administradores
- Ver listado de eventos
- Ver detalle de eventos
- Manejar estados de evento: borrador, publicado, cerrado y cancelado

### Gestion de parqueaderos

- Crear parqueadero al crear un evento
- Mostrar y ocultar campos dinamicos en formularios
- Gestionar capacidad, cupos disponibles y descripcion

### Control de roles

- Admin: acceso completo a crear, editar y eliminar eventos
- Usuario: acceso de solo lectura

## Bootstrap de administrador

El proyecto no versiona credenciales por defecto. Antes de ejecutar los seeders, define estas variables en tu archivo `.env`:

```env
ADMIN_BOOTSTRAP_NAME="Administrador"
ADMIN_BOOTSTRAP_EMAIL=admin@example.com
ADMIN_BOOTSTRAP_PASSWORD=DefineUnaClaveSeguraAqui
```

Si `ADMIN_BOOTSTRAP_EMAIL` o `ADMIN_BOOTSTRAP_PASSWORD` no estan definidas, el seeder omite la creacion del administrador.

## Instalacion y configuracion

### 1. Clonar repositorio

```bash
git clone <repositorio>
cd SistemaIntegracion
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales:

- `DB_CONNECTION=pgsql`
- `DB_HOST=tu-host-postgres`
- `DB_PORT=5432`
- `DB_DATABASE=tu-base-datos`
- `DB_USERNAME=tu-usuario`
- `DB_PASSWORD=tu-contrasena`
- `ADMIN_BOOTSTRAP_NAME=Administrador`
- `ADMIN_BOOTSTRAP_EMAIL=admin@example.com`
- `ADMIN_BOOTSTRAP_PASSWORD=DefineUnaClaveSeguraAqui`

### 4. Ejecutar migraciones y seeders

```bash
php artisan migrate:fresh --seed
```

### 5. Instalar dependencias frontend

```bash
npm install
```

### 6. Compilar assets

```bash
npm run build
```

### 7. Iniciar servidor

```bash
php artisan serve
```

La aplicacion estara disponible en `http://localhost:8000`.

## Rutas principales

### Publicas

- `GET /`
- `POST /login`
- `POST /register`

### Autenticadas

- `GET /dashboard`
- `GET /eventos`
- `GET /eventos/{evento}`

### Solo admin

- `GET /eventos/crear`
- `POST /eventos`
- `GET /eventos/{evento}/editar`
- `PATCH /eventos/{evento}`
- `DELETE /eventos/{evento}`

## Estructura general

```text
app/
database/
resources/views/
routes/
tests/
```

## Variables de entorno de referencia

```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=nombre_base
DB_USERNAME=usuario
DB_PASSWORD=contrasena_segura
DB_SSLMODE=require
```

## Notas

- Usa `.env.example` como plantilla.
- No subas credenciales reales al repositorio.
- El bootstrap del administrador depende de variables de entorno.

## Licencia

MIT
