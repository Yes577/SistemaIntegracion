<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Panel Usuario' }} | {{ config('app.name', 'Sistema de Gestion') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|space-grotesk:400,500,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="theme-shell theme-grid">
            <div class="mx-auto flex min-h-screen max-w-6xl flex-col gap-6 px-4 py-6 lg:px-8">
                <header class="glass-panel neon-outline px-6 py-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="section-eyebrow">User mobility deck</p>
                            <h1 class="mt-5 text-4xl font-black text-white">{{ $header ?? 'Dashboard usuario' }}</h1>
                            <p class="mt-3 text-soft">Hola, {{ auth()->user()->name }}. Tu panel esta conectado con la red de eventos y parqueadero.</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('dashboard') }}" class="theme-button-secondary">
                                <i class="bi bi-house-door"></i>
                                Dashboard
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

                @if (session('success'))
                    <div class="status-banner-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="status-banner-danger">{{ session('error') }}</div>
                @endif

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
