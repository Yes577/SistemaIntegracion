<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Panel Usuario' }} | {{ config('app.name', 'Sistema de Gestion') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-cyan-50 via-white to-slate-100 text-slate-900">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-8">
            <header class="mb-8 flex flex-col gap-4 rounded-3xl bg-slate-900 px-6 py-6 text-white shadow-xl sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">Panel usuario</p>
                    <h1 class="mt-2 text-3xl font-bold">{{ $header ?? 'Dashboard usuario' }}</h1>
                    <p class="mt-2 text-slate-300">Hola, {{ auth()->user()->name }}.</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-cyan-400 px-4 py-2 font-semibold text-slate-950 transition hover:bg-cyan-300">
                        Cerrar sesion
                    </button>
                </form>
            </header>

            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
