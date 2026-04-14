# Sistema de Gestión de Eventos - Instrucciones de Instalación

## Estado Actual
✅ Laravel 12 instalado
✅ Modelos creados (Evento, EstadoEvento, Parqueadero)
✅ Controladores implementados
✅ Policies configuradas
✅ Vistas Blade creadas
✅ Rutas configuradas
✅ Dependencias instaladas con Composer

## Pasos para Completar la Instalación

### 1. Crear archivo .env
Copia el archivo `.env.example` a `.env`:
```bash
cp .env.example .env
```

### 2. Generar APP_KEY
```bash
php artisan key:generate
```

### 3. Crear base de datos (SQLite)
```bash
touch database/database.sqlite
```

### 4. Ejecutar migraciones
```bash
php artisan migrate
```

### 5. Ejecutar seeders
```bash
php artisan db:seed
```

### 6. Compilar assets (si es necesario)
```bash
npm run dev
# o en producción: npm run build
```

## Estructura Creada

### Modelos
- `App\Models\Evento` - Eventos del sistema
- `App\Models\EstadoEvento` - Estados de eventos (borrador, publicado, cerrado, cancelado)
- `App\Models\Parqueadero` - Parqueaderos asociados a eventos

### Controladores
- `EventoController` - CRUD completo de eventos (con control de acceso)
- `DashboardController` - Dashboard principal

### Policies
- `EventoPolicy` - Control de acceso basado en roles

### Vistas
- `layouts/app.blade.php` - Layout principal con sidebar y navbar
- `dashboard.blade.php` - Panel principal
- `eventos/index.blade.php` - Listado de eventos
- `eventos/create.blade.php` - Crear evento (solo admin)
- `eventos/edit.blade.php` - Editar evento (solo admin)
- `eventos/show.blade.php` - Ver detalle de evento

### Seeders
- `EstadoEventoSeeder` - Crea los estados iniciales
- `DatabaseSeeder` - Ejecuta todos los seeders

## Rutas Disponibles

### Públicas (todos los usuarios)
- `GET /dashboard` - Dashboard
- `GET /eventos` - Listado de eventos
- `GET /eventos/{evento}` - Detalle de evento

### Solo Admin
- `GET /eventos/crear` - Crear evento
- `POST /eventos` - Guardar evento
- `GET /eventos/{evento}/editar` - Editar evento
- `PATCH /eventos/{evento}` - Actualizar evento
- `DELETE /eventos/{evento}` - Eliminar evento

## Control de Roles

El sistema verifica `id_tipo_rol` del usuario:
- **Admin**: `id_tipo_rol = 1`
  - Puede crear eventos
  - Puede editar eventos
  - Puede eliminar eventos
  - Puede registrar parqueaderos

- **Usuario Normal**: `id_tipo_rol = 2`
  - Solo puede ver eventos
  - No puede crear/editar/eliminar

## Datos iniciales

El seeder crea automáticamente:
1. Usuario admin: `admin@admin.com` / `admin123`
2. Estados de evento: borrador, publicado, cerrado, cancelado

## Iniciar servidor de desarrollo

```bash
php artisan serve
```

Luego accede a http://localhost:8000

## Características Implementadas

### Dashboard
- Bienvenida personalizada
- Recount de eventos totales
- Lista de próximos eventos
- Información de usuario logueado (nombre y rol)

### Gestión de Eventos
- Crear eventos (solo admin)
- Editar eventos (solo admin)
- Ver detalle de eventos
- Listar eventos con paginación
- Estados dinámicos (borrador, publicado, cerrado, cancelado)

### Sistema de Parqueaderos
- Crear parqueadero al crear evento
- Toggle dinámico: muestra/oculta campos si "tiene parqueadero"
- Editar información de parqueadero
- Ver detalles de parqueadero

### UI/UX
- Sidebar con navegación
- Navbar superior con info de usuario
- Formulario dinámico con JavaScript para mostrar/ocultar parqueadero
- Diseño responsive con Tailwind CSS
- Mensajes de éxito y error
- Paginación de eventos

## Próximos Pasos (Opcional)

1. Personalizar estilos en `resources/css/app.css`
2. Agregar validaciones adicionales
3. Implementar búsqueda y filtros
4. Agregar más campos a eventos/parqueaderos
5. Crear reportes
6. Agregar notificaciones
7. Implementar tests

## Notas Importantes

- El sistema usa Tailwind CSS para estilos (ya incluido en el proyecto)
- La autenticación ya está configurada (breeze/auth)
- El middleware 'admin' está configurado en `bootstrap/app.php`
- Las policies están automáticamente incorporadas en los controladores
