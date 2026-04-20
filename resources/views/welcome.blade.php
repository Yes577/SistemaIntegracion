<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Parqueadero de Eventos') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|space-grotesk:400,500,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    </head>
    <body>
        <main class="theme-shell theme-grid">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col justify-center px-6 py-12">
                <div class="grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                    <section class="animate-fade-up">
                        <span class="section-eyebrow">
                            <i class="bi bi-broadcast-pin"></i>
                            Ciudad, movilidad y eventos
                        </span>

                        <h1 class="mt-8 max-w-4xl text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                            Un frontend con energia de centro de control para parqueaderos y eventos.
                        </h1>
                        <p class="mt-6 max-w-3xl text-lg leading-8 text-soft">
                            Reunimos operacion del parqueadero, calendario de eventos, seguridad de acceso y paneles por rol bajo una identidad mas inmersiva, urbana y futurista.
                        </p>

                        <div class="mt-10 flex flex-wrap gap-4">
                            <a href="{{ route('login') }}" class="theme-button-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Iniciar sesion
                            </a>
                            <a href="{{ route('register') }}" class="theme-button-secondary">
                                <i class="bi bi-person-plus"></i>
                                Crear cuenta
                            </a>
                        </div>

                       
                    </section>

                    <section class="glass-panel neon-outline relative overflow-hidden p-8 sm:p-10 animate-fade-up">
                        <div class="absolute right-6 top-6 h-20 w-20 rounded-full bg-cyan-400/10 blur-2xl"></div>
                        <div class="absolute bottom-0 left-10 h-24 w-24 rounded-full bg-amber-400/10 blur-2xl"></div>

                        <h2 class="text-3xl font-bold text-white">Hub operacional</h2>
                        <p class="mt-3 text-soft">Todo el sistema habla el mismo lenguaje visual: pistas de circulacion, radar, cupos y activaciones de eventos.</p>

                        <div class="mt-8 space-y-4">
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-car-front-fill text-3xl text-cyan-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Parqueaderos con contexto</p>
                                    <p class="mt-1 text-sm text-soft">Capacidad, disponibilidad y detalle por evento desde tarjetas mas claras.</p>
                                </div>
                            </div>
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-calendar2-week-fill text-3xl text-amber-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Agenda de eventos</p>
                                    <p class="mt-1 text-sm text-soft">Consulta visual de fechas, sedes, estados y acceso inmediato a cada ficha.</p>
                                </div>
                            </div>
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-shield-lock-fill text-3xl text-emerald-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Acceso seguro</p>
                                    <p class="mt-1 text-sm text-soft">Autenticacion y paneles por rol integrados al mismo ecosistema de interfaz.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </body>
</html><!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Parqueadero de Eventos') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|space-grotesk:400,500,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    </head>
    <body>
        <main class="theme-shell theme-grid">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col justify-center px-6 py-12">
                <div class="grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                    <section class="animate-fade-up">
                        <span class="section-eyebrow">
                            <i class="bi bi-broadcast-pin"></i>
                            Ciudad, movilidad y eventos
                        </span>

                        <h1 class="mt-8 max-w-4xl text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                            Un frontend con energia de centro de control para parqueaderos y eventos.
                        </h1>
                        <p class="mt-6 max-w-3xl text-lg leading-8 text-soft">
                            Reunimos operacion del parqueadero, calendario de eventos, seguridad de acceso y paneles por rol bajo una identidad mas inmersiva, urbana y futurista.
                        </p>

                        <div class="mt-10 flex flex-wrap gap-4">
                            <a href="{{ route('login') }}" class="theme-button-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Iniciar sesion
                            </a>
                            <a href="{{ route('register') }}" class="theme-button-secondary">
                                <i class="bi bi-person-plus"></i>
                                Crear cuenta
                            </a>
                        </div>

                        
                    </section>

                    <section class="glass-panel neon-outline relative overflow-hidden p-8 sm:p-10 animate-fade-up">
                        <div class="absolute right-6 top-6 h-20 w-20 rounded-full bg-cyan-400/10 blur-2xl"></div>
                        <div class="absolute bottom-0 left-10 h-24 w-24 rounded-full bg-amber-400/10 blur-2xl"></div>

                        <h2 class="text-3xl font-bold text-white">Hub operacional</h2>
                        <p class="mt-3 text-soft">Todo el sistema habla el mismo lenguaje visual: pistas de circulacion, radar, cupos y activaciones de eventos.</p>

                        <div class="mt-8 space-y-4">
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-car-front-fill text-3xl text-cyan-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Parqueaderos con contexto</p>
                                    <p class="mt-1 text-sm text-soft">Capacidad, disponibilidad y detalle por evento desde tarjetas mas claras.</p>
                                </div>
                            </div>
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-calendar2-week-fill text-3xl text-amber-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Agenda de eventos</p>
                                    <p class="mt-1 text-sm text-soft">Consulta visual de fechas, sedes, estados y acceso inmediato a cada ficha.</p>
                                </div>
                            </div>
                            <div class="glass-panel-strong flex gap-4 p-5">
                                <i class="bi bi-shield-lock-fill text-3xl text-emerald-200"></i>
                                <div>
                                    <p class="text-lg font-semibold text-white">Acceso seguro</p>
                                    <p class="mt-1 text-sm text-soft">Autenticacion y paneles por rol integrados al mismo ecosistema de interfaz.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </body>
</html>
