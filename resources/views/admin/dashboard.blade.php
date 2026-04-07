<x-layouts.admin>
    <x-slot:title>Dashboard Admin</x-slot:title>
    <x-slot:header>Dashboard</x-slot:header>

    <div class="grid gap-6 md:grid-cols-3">
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Usuarios totales</p>
            <p class="mt-4 text-4xl font-bold text-slate-900">{{ $totalUsers }}</p>
        </section>

        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Administradores</p>
            <p class="mt-4 text-4xl font-bold text-cyan-600">{{ $totalAdmins }}</p>
        </section>

        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Usuarios estandar</p>
            <p class="mt-4 text-4xl font-bold text-slate-900">{{ $totalStandardUsers }}</p>
        </section>
    </div>

    <section class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-xl font-semibold">Accesos rapidos</h3>
        <p class="mt-2 text-slate-600">Desde aqui puedes administrar cuentas, editar roles y mantener el sistema centralizado.</p>

        <div class="mt-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white transition hover:bg-slate-700">
                Ir a gestion de usuarios
            </a>
        </div>
    </section>
</x-layouts.admin>
