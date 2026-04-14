<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Panel Administrador' }} | {{ config('app.name', 'Sistema de Gestion') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900">
        <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
            <aside class="bg-slate-900 px-6 py-8 text-white">
                <div class="mb-10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Administracion</p>
                    <h1 class="mt-2 text-2xl font-bold">Sistema de usuarios</h1>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 hover:bg-slate-700' }}">
                        Dashboard
                    </a>
                    
                    <!-- Sistema de Usuarios -->
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <p class="px-4 py-2 text-xs uppercase tracking-[0.2em] text-slate-500">Sistema de usuarios</p>
                        <a href="{{ route('admin.users.index') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 hover:bg-slate-700' }}">
                            Gestion de usuarios
                        </a>
                    </div>
                    
                    <!-- Gestion de Eventos -->
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <p class="px-4 py-2 text-xs uppercase tracking-[0.2em] text-slate-500">Gestion de eventos</p>
                        <a href="{{ route('admin.eventos.create') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.eventos.create') ? 'bg-blue-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                            Crear evento
                        </a>
                        <a href="{{ route('admin.eventos.index') }}" class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.eventos.index') ? 'bg-blue-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                            Ver eventos
                        </a>
                    </div>
                </nav>
            </aside>

            <div class="flex min-h-screen flex-col">
                <header class="border-b border-slate-200 bg-white">
                    <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Bienvenido, {{ auth()->user()->name }}</p>
                            <h2 class="text-2xl font-semibold">{{ $header ?? 'Panel administrador' }}</h2>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                                Cerrar sesion
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 px-6 py-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
