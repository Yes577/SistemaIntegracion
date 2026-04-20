<x-app-layout>
    <section class="glass-panel neon-outline p-8">
        <p class="section-eyebrow">Event creation</p>
        <h1 class="mt-4 text-4xl font-black text-white">Creacion de eventos centralizada</h1>
        <p class="mt-3 max-w-2xl text-soft">
            La configuracion de eventos y parqueaderos se gestiona desde el modulo administrativo para mantener control de roles y trazabilidad.
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.eventos.create') }}" class="theme-button-primary">
                <i class="bi bi-plus-circle"></i>
                Ir a crear evento
            </a>
            <a href="{{ route('eventos.index') }}" class="theme-button-secondary">Volver al listado</a>
        </div>
    </section>
</x-app-layout>
