# SistemaIntegracion

Sistema de gestion de usuarios desarrollado con Laravel, Breeze, Tailwind CSS y PostgreSQL en Azure.

## Stack

- Laravel 12
- Laravel Breeze
- Tailwind CSS
- PostgreSQL Azure
- Arquitectura MVC

## Funcionalidades

- Autenticacion nativa con Breeze
- Tabla independiente `tipo_rol` para roles
- Redireccion automatica segun rol
- Middleware para rutas admin y user
- Dashboard admin
- CRUD de usuarios para administradores
- Dashboard user con panel restringido


## Comandos

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```
