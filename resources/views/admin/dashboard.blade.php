<x-layouts.admin>
    <x-slot:title>Dashboard Admin</x-slot:title>
    <x-slot:header>Dashboard de operaciones</x-slot:header>

    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8">
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div>
                    <p class="section-eyebrow">Control </p>
                    <h3 class="mt-5 text-4xl font-black text-white">Supervisa usuarios, eventos y flujo del sistema.</h3>
                    <p class="mt-4 max-w-2xl text-base leading-8 text-soft">
                        El panel administrativo ahora se siente como una torre de operaciones: mas visual, mas claro y alineado con parqueadero + eventos.
                    </p>
                </div>
                <div class="glass-panel-strong p-6">
                    <p class="chip chip-warning">Admin </p>
                    <p class="mt-5 text-soft">Usuarios, roles, publicacion de eventos y accesos quedan integrados bajo el mismo lenguaje de interfaz.</p>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <article class="metric-card">
                <p class="chip chip-accent">Usuarios</p>
                <p class="metric-value">{{ $totalUsers }}</p>
                <p class="metric-label">Total de cuentas registradas.</p>
            </article>
            <article class="metric-card">
                <p class="chip chip-warning">Admins</p>
                <p class="metric-value">{{ $totalAdmins }}</p>
                <p class="metric-label">Perfiles con control ampliado.</p>
            </article>
            <article class="metric-card">
                <p class="chip">Usuarios base</p>
                <p class="metric-value">{{ $totalStandardUsers }}</p>
                <p class="metric-label">Cuentas operativas del sistema.</p>
            </article>
            <article class="metric-card">
                <p class="chip chip-success">Eventos</p>
                <p class="metric-value">{{ $totalEventos }}</p>
                <p class="metric-label">{{ $eventosPublicados }} publicados y visibles.</p>
            </article>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="glass-panel neon-outline p-7">
                <h3 class="text-2xl font-bold text-white">Gestion de usuarios</h3>
                <p class="mt-3 text-soft">Alta, edicion y control de permisos para el ecosistema.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.index') }}" class="theme-button-primary">
                        <i class="bi bi-people"></i>
                        Ver usuarios
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="theme-button-secondary">
                        <i class="bi bi-person-plus"></i>
                        Crear usuario
                    </a>
                </div>
            </div>

            <div class="glass-panel neon-outline p-7">
                <h3 class="text-2xl font-bold text-white">Gestion de eventos</h3>
                <p class="mt-3 text-soft">Coordina programacion, estado y configuracion de parqueaderos por evento.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.eventos.create') }}" class="theme-button-primary">
                        <i class="bi bi-plus-circle"></i>
                        Crear evento
                    </a>
                    <a href="{{ route('admin.eventos.index') }}" class="theme-button-secondary">
                        <i class="bi bi-calendar2-week"></i>
                        Ver eventos
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
