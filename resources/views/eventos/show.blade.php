<x-app-layout>
    <div class="space-y-8">
        <a href="{{ route('eventos.index') }}" class="topbar-link w-fit">
            <i class="bi bi-arrow-left"></i>
            Volver a eventos
        </a>

        <section class="glass-panel neon-outline overflow-hidden p-8">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="chip chip-accent">{{ $evento->estado->nombre }}</span>
                        <span class="chip {{ $evento->tiene_parqueadero ? 'chip-success' : '' }}">
                            <i class="bi bi-p-circle"></i>
                            {{ $evento->tiene_parqueadero ? 'Con parqueadero' : 'Sin parqueadero' }}
                        </span>
                    </div>

                    <h1 class="mt-6 text-4xl font-black text-white sm:text-5xl">{{ $evento->nombre }}</h1>
                    <p class="mt-5 max-w-3xl text-base leading-8 text-soft">{{ $evento->descripcion }}</p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div class="metric-card">
                            <p class="chip">Fecha</p>
                            <p class="mt-4 text-2xl font-bold text-white">{{ $evento->fecha->format('d/m/Y') }}</p>
                            <p class="metric-label">Hora de inicio: {{ $evento->hora->format('H:i') }}</p>
                        </div>
                        <div class="metric-card">
                            <p class="chip">Aforo</p>
                            <p class="mt-4 text-2xl font-bold text-white">{{ $evento->capacidad_actual }} / {{ $evento->capacidad_maxima }}</p>
                            <p class="metric-label">Cupos disponibles / Capacidad maxima.</p>
                        </div>
                    </div>
                </div>

                <aside class="glass-panel-strong p-6">
                    <h2 class="text-2xl font-bold text-white">Ficha operativa</h2>
                    <dl class="mt-6 space-y-5 text-sm text-soft">
                        <div>
                            <dt class="text-xs uppercase tracking-[0.24em] text-muted">Lugar</dt>
                            <dd class="mt-1 text-base text-white">{{ $evento->lugar }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-[0.24em] text-muted">Organizador</dt>
                            <dd class="mt-1 text-base text-white">{{ $evento->usuario->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-[0.24em] text-muted">Creado</dt>
                            <dd class="mt-1 text-base text-white">{{ $evento->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-[0.24em] text-muted">Ultima actualizacion</dt>
                            <dd class="mt-1 text-base text-white">{{ $evento->updated_at->diffForHumans() }}</dd>
                        </div>
                    </dl>

                    @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
                        <div class="mt-8 flex flex-wrap gap-2">
                            <a href="{{ route('admin.eventos.edit', $evento) }}" class="theme-button-secondary">
                                <i class="bi bi-pencil-square"></i>
                                Editar
                            </a>
                            <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este evento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="theme-button-danger">
                                    <i class="bi bi-trash3"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @elseif(auth()->user() && auth()->user()->id_tipo_rol === 2)
                        @php
                            $inscripcion = $evento->inscripciones->firstWhere('id_user', auth()->id());
                            $yaInscrito  = !is_null($inscripcion);
                            $esPublicado = $evento->estado->nombre === 'publicado';
                        @endphp
                        <div class="mt-8 flex flex-wrap gap-2">
                            @if($yaInscrito)
                                @if($inscripcion->id_parqueadero)
                                    <span class="chip chip-success">
                                        <i class="bi bi-check-circle"></i> Inscrito al evento + parqueadero
                                    </span>
                                @else
                                    <span class="chip chip-success">
                                        <i class="bi bi-check-circle"></i> Inscrito al evento
                                    </span>
                                @endif
                                <form action="{{ route('panel.inscripciones.destroy', $evento) }}" method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas cancelar tu inscripción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="theme-button-danger">
                                        <i class="bi bi-person-dash"></i>
                                        Cancelar inscripción
                                    </button>
                                </form>
                            @elseif($esPublicado && $evento->capacidad_actual > 0)
                                <form action="{{ route('panel.inscripciones.store', $evento) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="theme-button-primary">
                                        <i class="bi bi-person-plus"></i>
                                        Inscribirse al evento
                                    </button>
                                </form>
                                @if($evento->tiene_parqueadero && $evento->parqueadero && $evento->parqueadero->cupos_disponibles > 0)
                                    <form action="{{ route('panel.inscripciones.store', $evento) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="con_parqueadero" value="1">
                                        <button type="submit" class="theme-button-secondary">
                                            <i class="bi bi-p-circle"></i>
                                            Inscribirse + Parqueadero
                                        </button>
                                    </form>
                                @endif
                            @elseif($esPublicado && $evento->capacidad_actual <= 0)
                                <span class="chip"><i class="bi bi-x-circle"></i> Sin cupos disponibles</span>
                            @else
                                <span class="chip"><i class="bi bi-lock"></i> {{ ucfirst($evento->estado->nombre) }}</span>
                            @endif
                        </div>
                    @endif
                </aside>
            </div>
        </section>

        @if($evento->tiene_parqueadero && $evento->parqueadero)
            <section class="glass-panel neon-outline p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="section-eyebrow">Parking zone</p>
                        <h2 class="mt-4 text-3xl font-bold text-white">Informacion del parqueadero asociado</h2>
                    </div>
                    <span class="chip chip-success">Activo en este evento</span>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-3">
                    <article class="metric-card">
                        <p class="chip">Capacidad total</p>
                        <p class="metric-value">{{ $evento->parqueadero->capacidad_total }}</p>
                        <p class="metric-label">Espacios configurados.</p>
                    </article>
                    <article class="metric-card">
                        <p class="chip chip-success">Disponibles</p>
                        <p class="metric-value">{{ $evento->parqueadero->cupos_disponibles }}</p>
                        <p class="metric-label">Cupos libres para ingreso.</p>
                    </article>
                    <article class="metric-card">
                        <p class="chip chip-warning">Cobertura</p>
                        <p class="metric-value">{{ $evento->capacidad_maxima > 0 ? round(($evento->parqueadero->capacidad_total / $evento->capacidad_maxima) * 100) : 0 }}%</p>
                        <p class="metric-label">Relacion entre aforo y parqueadero.</p>
                    </article>
                </div>

                @if($evento->parqueadero->descripcion)
                    <div class="mt-8 rounded-[1.6rem] border border-cyan-400/15 bg-cyan-400/5 p-5 text-soft">
                        {{ $evento->parqueadero->descripcion }}
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-app-layout>
