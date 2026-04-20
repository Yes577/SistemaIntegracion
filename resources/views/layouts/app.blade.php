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
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
                <header class="glass-panel neon-outline mb-8 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-[1.6rem] bg-cyan-400/12 text-3xl text-cyan-200">
                                <i class="bi bi-car-front-fill"></i>
                            </span>
                            <div>
                                <p class="section-eyebrow">Smart parking district</p>
                                <h1 class="mt-4 text-2xl font-black text-white sm:text-3xl">Centro de control para eventos y parqueadero</h1>
                                <p class="mt-3 max-w-2xl text-sm text-soft">Visualiza flujo de eventos, ocupacion y operacion desde una interfaz tipo hub urbano.</p>
                            </div>
                        </div>

                        @auth
                            <div class="flex flex-col gap-3 lg:items-end">
                                <div class="glass-panel-strong flex items-center gap-3 px-4 py-3">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-400/10 text-xl text-amber-200">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs uppercase tracking-[0.3em] text-muted">{{ auth()->user()->id_tipo_rol === 1 ? 'Administrador' : 'Usuario' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard') }}" class="topbar-link {{ request()->routeIs('dashboard') ? 'topbar-link-active' : '' }}">
                                        <i class="bi bi-speedometer2"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('eventos.index') }}" class="topbar-link {{ request()->routeIs('eventos.*') ? 'topbar-link-active' : '' }}">
                                        <i class="bi bi-calendar2-event"></i>
                                        Eventos
                                    </a>
                                    @if(auth()->user()->id_tipo_rol === 1)
                                        <a href="{{ route('admin.dashboard') }}" class="topbar-link {{ request()->routeIs('admin.*') ? 'topbar-link-active' : '' }}">
                                            <i class="bi bi-shield-lock"></i>
                                            Admin
                                        </a>
                                    @else
                                        <a href="{{ route('panel.dashboard') }}" class="topbar-link {{ request()->routeIs('panel.*') ? 'topbar-link-active' : '' }}">
                                            <i class="bi bi-grid-1x2"></i>
                                            Panel
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="topbar-link {{ request()->routeIs('profile.*') ? 'topbar-link-active' : '' }}">
                                        <i class="bi bi-sliders"></i>
                                        Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="theme-button-danger">
                                            <i class="bi bi-box-arrow-right"></i>
                                            Salir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </header>

                <main class="flex-1">
                    @if($errors->any())
                        <div class="status-banner-danger">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                                <div>
                                    <p class="font-semibold">Tienes errores</p>
                                    <ul class="mt-2 list-inside list-disc text-sm text-rose-100">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="status-banner-success">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-2xl"></i>
                                <p class="text-sm font-semibold">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
