<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Panel Administrador' }} | {{ config('app.name', 'Sistema de Gestion') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|space-grotesk:400,500,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="theme-shell theme-grid">
            <div class="mx-auto grid min-h-screen max-w-7xl gap-6 px-4 py-6 lg:grid-cols-[280px_1fr] lg:px-8">
                <aside class="glass-panel neon-outline h-fit p-6">
                    <p class="section-eyebrow">Admin command</p>
                    <h1 class="mt-5 text-3xl font-black text-white">Circuito maestro</h1>
                    <p class="mt-3 text-sm text-soft">Gestion central para operadores, eventos y usuarios del ecosistema.</p>

                    <nav class="mt-8 space-y-3">
                        <a href="{{ route('admin.dashboard') }}" class="topbar-link w-full justify-start {{ request()->routeIs('admin.dashboard') ? 'topbar-link-active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="topbar-link w-full justify-start {{ request()->routeIs('admin.users.*') ? 'topbar-link-active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            Usuarios
                        </a>
                        <a href="{{ route('admin.eventos.index') }}" class="topbar-link w-full justify-start {{ request()->routeIs('admin.eventos.index') ? 'topbar-link-active' : '' }}">
                            <i class="bi bi-calendar2-week"></i>
                            Eventos
                        </a>
                        <a href="{{ route('admin.eventos.create') }}" class="topbar-link w-full justify-start {{ request()->routeIs('admin.eventos.create') ? 'topbar-link-active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            Nuevo evento
                        </a>
                        <a href="{{ route('dashboard') }}" class="topbar-link w-full justify-start">
                            <i class="bi bi-arrow-left-circle"></i>
                            Volver al dashboard
                        </a>
                    </nav>
                </aside>

                <div class="flex min-h-screen flex-col gap-6">
                    <header class="glass-panel neon-outline px-6 py-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.3em] text-cyan-200">Admin / control tower</p>
                                <h2 class="mt-2 text-3xl font-black text-white">{{ $header ?? 'Panel administrador' }}</h2>
                                <p class="mt-2 text-soft">Hola, {{ auth()->user()->name }}. Coordina accesos, usuarios y programacion del distrito.</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('profile.edit') }}" class="theme-button-secondary">
                                    <i class="bi bi-sliders"></i>
                                    Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="theme-button-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Cerrar sesion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1">
                        @if (session('success'))
                            <div class="status-banner-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="status-banner-danger">{{ session('error') }}</div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
