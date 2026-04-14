<x-layouts.admin>
    <x-slot:title>Dashboard Admin</x-slot:title>
    <x-slot:header>Dashboard</x-slot:header>

    <!-- Estadísticas Generales -->
    <section class="mb-8">
        <h3 class="mb-4 text-xl font-semibold text-slate-900">Estadísticas del sistema</h3>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Usuarios totales</p>
                <p class="mt-4 text-4xl font-bold text-slate-900">{{ $totalUsers }}</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Administradores</p>
                <p class="mt-4 text-4xl font-bold text-cyan-600">{{ $totalAdmins }}</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Usuarios estándar</p>
                <p class="mt-4 text-4xl font-bold text-slate-900">{{ $totalStandardUsers }}</p>
            </div>
        </div>
    </section>

    <!-- Estadísticas de Eventos -->
    <section class="mb-8">
        <h3 class="mb-4 text-xl font-semibold text-slate-900">Estadísticas de eventos</h3>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Eventos totales</p>
                <p class="mt-4 text-4xl font-bold text-blue-600">{{ $totalEventos }}</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Eventos publicados</p>
                <p class="mt-4 text-4xl font-bold text-green-600">{{ $eventosPublicados }}</p>
            </div>
        </div>
    </section>

    <!-- Paneles de Gestión -->
    <section class="grid gap-6 md:grid-cols-2">
        <!-- Sistema de Usuarios -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-xl font-semibold text-slate-900">Sistema de usuarios</h3>
            <p class="mt-2 text-slate-600">Administra cuentas de usuario, roles y permisos del sistema.</p>
            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="inline-flex rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white transition hover:bg-slate-700">
                    Ir a gestión de usuarios
                </a>
            </div>
        </div>

        <!-- Gestión de Eventos -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-xl font-semibold text-slate-900">Gestión de eventos</h3>
            <p class="mt-2 text-slate-600">Crea, edita y administra todos los eventos del sistema.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.eventos.create') }}" class="inline-flex rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">
                    Crear evento
                </a>
                <a href="{{ route('admin.eventos.index') }}" class="inline-flex rounded-xl bg-green-600 px-5 py-3 font-semibold text-white transition hover:bg-green-700">
                    Ver eventos
                </a>
            </div>
        </div>
    </section>
</x-layouts.admin>
