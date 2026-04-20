<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema de Eventos y Parqueadero') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|space-grotesk:400,500,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="theme-shell theme-grid">
            <div class="mx-auto grid min-h-screen max-w-7xl gap-10 px-4 py-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:px-8">
                <section class="relative overflow-hidden rounded-[2.6rem] border border-white/10 bg-slate-950/55 p-8 shadow-2xl backdrop-blur-xl sm:p-10">
                    <div class="hero-orbit left-[-6rem] top-[-5rem] h-48 w-48"></div>
                    <div class="hero-orbit bottom-[-7rem] right-[-4rem] h-60 w-60 [animation-duration:22s]"></div>

                    <p class="section-eyebrow">Parking event nexus</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight text-white sm:text-6xl">Ingresa al distrito inteligente de movilidad y eventos.</h1>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-soft">
                        Control de accesos, eventos activos, zonas de parqueo y operacion diaria en una sola experiencia visual con tono futurista.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="metric-card">
                            <p class="chip chip-accent">Accesos</p>
                            <p class="metric-value">24/7</p>
                            <p class="metric-label">Monitoreo continuo del flujo vehicular y entradas.</p>
                        </div>
                        <div class="metric-card">
                            <p class="chip chip-warning">Zonas</p>
                            <p class="metric-value">Live</p>
                            <p class="metric-label">Bloques, cupos y eventos sincronizados en tiempo real.</p>
                        </div>
                        <div class="metric-card">
                            <p class="chip chip-success">Seguridad</p>
                            <p class="metric-value">AES</p>
                            <p class="metric-label">Autenticacion protegida para operadores y administradores.</p>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-wrap gap-3 text-sm text-soft">
                        <span class="chip">Eventos en ruta</span>
                        <span class="chip">Panel operativo</span>
                        <span class="chip">Control de cupos</span>
                    </div>
                </section>

                <div class="glass-panel neon-outline w-full max-w-2xl justify-self-center p-8 sm:p-10">
                    <div class="mb-8 flex items-center gap-4">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-[1.6rem] bg-amber-400/10 text-3xl text-amber-200">
                            <i class="bi bi-radar"></i>
                        </span>
                        <div>
                            <p class="text-sm uppercase tracking-[0.35em] text-cyan-200">Control access</p>
                            <h2 class="mt-2 text-3xl font-bold text-white">Parqueadero + eventos</h2>
                        </div>
                    </div>

                    {{ $slot }}

                    <div class="mt-8 border-t border-white/10 pt-6 text-sm text-soft">
                        Operacion visual limpia, segura y preparada para usuarios del sistema y administradores.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
