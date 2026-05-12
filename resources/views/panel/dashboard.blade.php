<x-layouts.user>
    <x-slot:title>Dashboard Usuario</x-slot:title>
    <x-slot:header>Panel de movilidad</x-slot:header>

    <div class="space-y-6">
        <section class="grid gap-4 md:grid-cols-2">
            <a href="{{ route('eventos.index') }}" class="event-card">
                <i class="bi bi-calendar2-week text-3xl text-cyan-200"></i>
                <h3 class="mt-5 text-2xl font-bold text-white">Explorar eventos</h3>
                <p class="mt-3 text-soft">Consulta fechas, lugares, estados y si cada evento tiene parqueadero asociado.</p>
            </a>
            <a href="{{ route('profile.edit') }}" class="event-card">
                <i class="bi bi-person-gear text-3xl text-amber-200"></i>
                <h3 class="mt-5 text-2xl font-bold text-white">Configurar perfil</h3>
                <p class="mt-3 text-soft">Ajusta tus datos y mantente listo para recibir notificaciones del sistema.</p>
            </a>
        </section>

        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="section-eyebrow">Mis accesos</p>
                    <h2 class="mt-4 text-3xl font-bold text-white">Tus inscripciones recientes</h2>
                    <p class="mt-3 text-soft">Desde aqui puedes revisar el estado del QR y abrirlo para mostrarlo el dia del evento.</p>
                </div>
            </div>

            @if($inscripciones->count() > 0)
                <div class="mt-8 grid gap-4 lg:grid-cols-2">
                    @foreach($inscripciones as $inscripcion)
                        <article class="event-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="chip chip-accent">{{ $inscripcion->evento->estado->nombre }}</span>
                                        @if($inscripcion->estado_check_in === \App\Models\Inscripcion::STATUS_CONFIRMED)
                                            <span class="chip chip-success">Check-in confirmado</span>
                                        @elseif($inscripcion->isQrExpired())
                                            <span class="chip chip-warning">QR expirado</span>
                                        @else
                                            <span class="chip">Pendiente</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-5 text-2xl font-bold text-white">{{ $inscripcion->evento->nombre }}</h3>
                                    <p class="mt-2 text-soft">{{ $inscripcion->evento->lugar }}</p>
                                </div>
                                <i class="bi bi-ticket-perforated text-3xl text-cyan-200"></i>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-2 text-sm text-soft">
                                <span class="chip">{{ $inscripcion->evento->fecha->format('d/m/Y') }}</span>
                                <span class="chip">{{ $inscripcion->evento->hora->format('H:i') }}</span>
                                <span class="chip">{{ $inscripcion->id_parqueadero ? 'Con parqueadero' : 'Sin parqueadero' }}</span>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-2">
                                <a href="{{ route('eventos.show', $inscripcion->evento) }}" class="theme-button-secondary">
                                    <i class="bi bi-eye"></i>
                                    Ver evento
                                </a>
                                <a href="{{ route('inscripciones.qr', $inscripcion) }}" class="theme-button-primary" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-qr-code"></i>
                                    Abrir QR
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-[1.6rem] border border-dashed border-white/10 bg-white/[0.02] p-8 text-center text-soft">
                    Todavia no tienes inscripciones activas. Cuando reserves un evento, tu QR aparecera aqui.
                </div>
            @endif
        </section>
    </div>
</x-layouts.user>
