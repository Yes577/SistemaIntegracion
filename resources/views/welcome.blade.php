<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Sistema de Gestion de Usuarios') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-white">
        <main class="mx-auto flex min-h-screen max-w-7xl items-center px-6 py-16">
            <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <section>
                    <p class="text-sm uppercase tracking-[0.4em] text-cyan-300">Laravel + Breeze + PostgreSQL Azure</p>
                    <h1 class="mt-6 max-w-3xl text-5xl font-black leading-tight">
                        Sistema de gestion de usuarios con paneles separados por rol.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg text-slate-300">
                        Accede con autenticacion nativa de Laravel y redireccion automatica segun el rol almacenado en la tabla independiente <span class="font-semibold text-cyan-300">tipo_rol</span>.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="rounded-2xl bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                            Iniciar sesion
                        </a>
                        <a href="{{ route('register') }}" class="rounded-2xl border border-slate-700 px-6 py-3 font-semibold text-white transition hover:border-slate-500 hover:bg-slate-900">
                            Registrar usuario
                        </a>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-slate-800 bg-white/5 p-8 backdrop-blur">
                    <h2 class="text-2xl font-bold">Caracteristicas incluidas</h2>
                    <ul class="mt-6 space-y-4 text-slate-300">
                        <li>CRUD completo de usuarios para administradores</li>
                        <li>Panel de usuario restringido y sin acciones</li>
                        <li>Middleware por rol para rutas `/admin/*` y `/panel/*`</li>
                        <li>Seeder con roles base y administrador por defecto</li>
                    </ul>
                </section>
            </div>
        </main>
    </body>
</html>
