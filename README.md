# SistemaIntegracion

Sistema de Gestión de Eventos desarrollado con Laravel 12, Tailwind CSS y PostgreSQL.

## Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de Datos**: PostgreSQL
- **Autenticación**: Laravel Breeze
- **Build Tool**: Vite

## Características

### 🎯 Gestión de Eventos
- ✅ Crear eventos (solo admin)
- ✅ Editar eventos (solo admin)
- ✅ Eliminar eventos (solo admin)
- ✅ Ver listado de eventos
- ✅ Ver detalle de eventos
- ✅ Sistema de estados (borrador, publicado, cerrado, cancelado)

### 🅿️ Gestión de Parqueaderos
- ✅ Crear parqueadero al crear evento
- ✅ Formulario dinámico (muestra/oculta campos)
- ✅ Información: capacidad, cupos disponibles, descripción

### 👥 Control de Roles
- **Admin**: Acceso completo a crear/editar/eliminar eventos
- **Usuario**: Solo visualización de eventos

### 🎨 UI/UX
- Sidebar con navegación
- Navbar superior con información de usuario
- Diseño responsive con Tailwind CSS
- Formularios dinámicos con JavaScript
- Paginación de eventos

## Credenciales de Acceso

### Usuario Admin

| Campo | Valor |
|-------|-------|
| **Email** | `admin@admin.com` |
| **Contraseña** | `admin123` |
| **Rol** | Administrador |

**Permisos:**
- Crear eventos
- Editar eventos
- Eliminar eventos
- Crear parqueaderos

## Instalación y Configuración

### 1. Clonar repositorio
```bash
git clone <repositorio>
cd SistemaIntegracion
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con tus credenciales de PostgreSQL:
- `DB_CONNECTION=pgsql`
- `DB_HOST=tu-host-postgres`
- `DB_PORT=5432`
- `DB_DATABASE=tu-base-datos`
- `DB_USERNAME=tu-usuario`
- `DB_PASSWORD=tu-contraseña`

### 4. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 5. Instalar dependencias de npm
```bash
npm install
```

### 6. Compilar assets
```bash
npm run build
```

### 7. Iniciar servidor de desarrollo
```bash
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

## Notas Importantes

- Asegúrate de tener PostgreSQL instalado y configurado en tu sistema.
- Si usas Docker o un contenedor, ajusta las variables de entorno en consecuencia.
- Para desarrollo, puedes usar una base de datos local de PostgreSQL.

## Rutas Disponibles

### Públicas (sin autenticación)
- `GET /` - Home
- `POST /login` - Login
- `POST /register` - Registro

### Autenticadas (todos los usuarios)
- `GET /dashboard` - Dashboard
- `GET /eventos` - Listado de eventos
- `GET /eventos/{evento}` - Detalle de evento

### Solo Admin
- `GET /eventos/crear` - Crear evento
- `POST /eventos` - Guardar evento
- `GET /eventos/{evento}/editar` - Editar evento
- `PATCH /eventos/{evento}` - Actualizar evento
- `DELETE /eventos/{evento}` - Eliminar evento

## Estructura del Proyecto

```
app/
├── Models/
│   ├── Evento.php
│   ├── EstadoEvento.php
│   ├── Parqueadero.php
│   └── User.php
├── Http/
│   ├── Controllers/
│   │   ├── EventoController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── AdminMiddleware.php
└── Policies/
    └── EventoPolicy.php

database/
├── migrations/
│   ├── 2026_04_14_191328_create_estados_evento_table.php
│   ├── 2026_04_14_191335_create_eventos_table.php
│   └── 2026_04_14_191343_create_parqueaderos_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── TipoRolSeeder.php
    └── EstadoEventoSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php
├── dashboard.blade.php
└── eventos/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── show.blade.php
```

## Modelos de Datos

### Usuarios
- id
- name
- email
- password
- id_tipo_rol
- email_verified_at
- timestamps

### Eventos
- id
- nombre
- descripcion
- fecha
- hora
- lugar
- tiene_parqueadero (boolean)
- capacidad_maxima
- id_estado (FK)
- id_usuario (FK)
- timestamps
- soft deletes

### Estados de Evento
- id
- nombre (borrador, publicado, cerrado, cancelado)
- descripcion
- timestamps

### Parqueaderos
- id
- id_usuario (FK)
- id_evento (FK)
- capacidad_total
- cupos_disponibles
- descripcion
- timestamps
- soft deletes

## Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Resetear base de datos
php artisan migrate:reset

# Resetear y ejecutar migrations + seeders
php artisan migrate:fresh --seed

# Crear usuario de prueba
php artisan tinker
User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => bcrypt('password'), 'id_tipo_rol' => 2]);

# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build

# Iniciar servidor
php artisan serve

# Ver logs
php artisan tinker Log::info('mensaje')
```

## Variables de Entorno

```env
DB_CONNECTION=pgsql
DB_HOST=oscar.postgres.database.azure.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=oscar
DB_PASSWORD="**********"
DB_SSLMODE=require

## Notas Importantes

- El sistema utiliza SQLite como base de datos por defecto
- La autenticación usa Laravel Breeze
- Los estilos están compilados con Vite
- Las vistas usan Tailwind CSS
- El control de acceso se implementa con Policies

## Licencia

MIT

## Autor

Desarrollado para Universidad del Magdalena
