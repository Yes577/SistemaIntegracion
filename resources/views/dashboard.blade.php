<x-app-layout>
    <div class="space-y-8">
        <section class="glass-panel neon-outline overflow-hidden p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div>
                    <p class="section-eyebrow">Live district overview</p>
                    <h1 class="mt-6 text-4xl font-black text-white sm:text-5xl">Bienvenido, {{ auth()->user()->name }}.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-soft">
                        Este centro de control unifica eventos, flujo de parqueadero y operacion diaria con una lectura mas clara, inmersiva y rapida.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('eventos.index') }}" class="theme-button-primary">
                            <i class="bi bi-calendar2-week"></i>
                            Ver eventos
                        </a>
                        <a href="{{ route('profile.edit') }}" class="theme-button-secondary">
                            <i class="bi bi-person-gear"></i>
                            Ajustar perfil
                        </a>
                    </div>
                </div>

                <div class="glass-panel-strong p-6">
                    <p class="chip chip-warning">Nodo activo</p>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm uppercase tracking-[0.24em] text-muted">Rol</p>
                            <p class="mt-2 text-xl font-bold text-white">{{ auth()->user()->id_tipo_rol === 1 ? 'Administrador' : 'Usuario' }}</p>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.24em] text-muted">Eventos rastreados</p>
                            <p class="mt-2 text-xl font-bold text-white">{{ $totalEventos }}</p>
                        </div>
                    </div>
                    <div class="mt-6 rounded-[1.5rem] border border-cyan-400/15 bg-cyan-400/5 p-4">
                        <p class="text-sm text-soft">La interfaz prioriza estado de eventos, accesos y lectura rapida del parqueadero desde una sola capa visual.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-3">
            <article class="metric-card">
                <p class="chip chip-accent">Eventos</p>
                <p class="metric-value">{{ $totalEventos }}</p>
                <p class="metric-label">Eventos totales dentro del ecosistema conectado.</p>
            </article>
            <article class="metric-card">
                <p class="chip chip-success">Proximos</p>
                <p class="metric-value">{{ $eventosProximos->count() }}</p>
                <p class="metric-label">Eventos con fecha futura listos para seguimiento.</p>
            </article>
            
        </section>

        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="section-eyebrow">Event stream</p>
                    <h2 class="mt-4 text-3xl font-bold text-white">Eventos en aproximacion</h2>
                    <p class="mt-2 text-soft">Una vista rapida de las activaciones que impactan flujo y capacidad.</p>
                </div>
                @if(auth()->user()->id_tipo_rol === 1)
                    <a href="{{ route('admin.eventos.create') }}" class="theme-button-primary">
                        <i class="bi bi-plus-lg"></i>
                        Crear evento
                    </a>
                @endif
            </div>

            @if($eventosProximos->count() > 0)
                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    @foreach($eventosProximos as $evento)
                        <a href="{{ route('eventos.show', $evento) }}" class="event-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="chip chip-accent">{{ $evento->fecha->format('d/m/Y') }}</p>
                                    <h3 class="mt-4 text-2xl font-bold text-white">{{ $evento->nombre }}</h3>
                                    <p class="mt-2 text-soft">{{ $evento->lugar }}</p>
                                </div>
                                <i class="bi bi-arrow-up-right-circle text-2xl text-cyan-200"></i>
                            </div>
                            <p class="mt-5 text-sm leading-7 text-soft">{{ Str::limit($evento->descripcion, 110) }}</p>
                            <div class="mt-6 flex flex-wrap gap-2">
                                <span class="chip">{{ $evento->hora->format('H:i') }}</span>
                                <span class="chip {{ $evento->tiene_parqueadero ? 'chip-success' : '' }}">
                                    <i class="bi bi-p-circle"></i>
                                    {{ $evento->tiene_parqueadero ? 'Con parqueadero' : 'Sin parqueadero' }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mt-8 glass-panel-strong p-8 text-center text-soft">
                    No hay eventos proximos. Cuando se registren, apareceran aqui con contexto operativo.
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
