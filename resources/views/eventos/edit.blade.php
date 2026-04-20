<x-app-layout>
    <section class="glass-panel neon-outline p-8">
        <p class="section-eyebrow">Event edition</p>
        <h1 class="mt-4 text-4xl font-black text-white">Edicion de eventos desde admin</h1>
        <p class="mt-3 max-w-2xl text-soft">
            El ajuste de datos operativos y de parqueadero se realiza en el panel administrativo para mantener consistencia del sistema.
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.eventos.edit', $evento) }}" class="theme-button-primary">
                <i class="bi bi-pencil-square"></i>
                Editar en admin
            </a>
            <a href="{{ route('eventos.show', $evento) }}" class="theme-button-secondary">Volver al detalle</a>
        </div>
    </section>
</x-app-layout>
