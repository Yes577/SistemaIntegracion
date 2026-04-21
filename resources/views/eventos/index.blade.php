<x-app-layout>
    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="section-eyebrow">Event radar</p>
                    <h1 class="mt-4 text-4xl font-black text-white">Mapa de eventos del sistema</h1>
                    <p class="mt-3 max-w-2xl text-soft">Explora cada activacion con su fecha, lugar, estado y relacion con parqueadero.</p>
                </div>
                @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
                    <a href="{{ route('admin.eventos.create') }}" class="theme-button-primary">
                        <i class="bi bi-plus-lg"></i>
                        Crear evento
                    </a>
                @endif
            </div>
        </section>

        @if($eventos->count() > 0)
            <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($eventos as $evento)
                    <article class="event-card">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="chip chip-accent">{{ $evento->estado->nombre }}</span>
                                    @if($evento->tiene_parqueadero)
                                        <span class="chip chip-success"><i class="bi bi-p-circle"></i> Parqueadero</span>
                                    @endif
                                </div>
                                <h3 class="mt-5 text-2xl font-bold text-white">{{ $evento->nombre }}</h3>
                                <p class="mt-3 text-sm leading-7 text-soft">{{ Str::limit($evento->descripcion, 110) }}</p>
                            </div>
                            <i class="bi bi-broadcast text-2xl text-cyan-200"></i>
                        </div>

                        <div class="mt-6 space-y-3 text-sm text-soft">
                            <p><i class="bi bi-calendar3 me-2 text-cyan-200"></i>{{ $evento->fecha->format('d/m/Y') }} a las {{ $evento->hora->format('H:i') }}</p>
                            <p><i class="bi bi-geo-alt me-2 text-amber-200"></i>{{ $evento->lugar }}</p>
                            <p><i class="bi bi-people me-2 text-emerald-200"></i>Capacidad: {{ $evento->capacidad_actual }} / {{ $evento->capacidad_maxima }} cupos disponibles</p>
                            @if($evento->tiene_parqueadero && $evento->parqueadero && $evento->estado->nombre === 'publicado')
                                <p><i class="bi bi-p-circle me-2 text-violet-300"></i>Parqueadero: {{ $evento->parqueadero->cupos_disponibles }} / {{ $evento->parqueadero->capacidad_total }} cupos disponibles</p>
                            @endif
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('eventos.show', $evento) }}" class="theme-button-primary">
                                <i class="bi bi-eye"></i>
                                Ver detalle
                            </a>
                            @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
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
                            @elseif(auth()->user() && auth()->user()->id_tipo_rol === 2)
                                @php
                                    $inscripcion = $evento->inscripciones->firstWhere('id_user', auth()->id());
                                    $yaInscrito  = !is_null($inscripcion);
                                    $esPublicado = $evento->estado->nombre === 'publicado';
                                @endphp
                                @if($yaInscrito)
                                    {{-- Badge según si tiene parqueadero o no --}}
                                    @if($inscripcion->id_parqueadero)
                                        <span class="chip chip-success">
                                            <i class="bi bi-check-circle"></i> Inscrito + parqueadero
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
                                    {{-- Publicado con cupos: botones de inscripción --}}
                                    <form action="{{ route('panel.inscripciones.store', $evento) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="theme-button-primary">
                                            <i class="bi bi-person-plus"></i>
                                            Inscribirse
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
                                    <span class="chip"><i class="bi bi-x-circle"></i> Sin cupos</span>
                                @else
                                    {{-- cerrado o cancelado --}}
                                    <span class="chip"><i class="bi bi-lock"></i> {{ ucfirst($evento->estado->nombre) }}</span>
                                @endif
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="glass-panel p-4">
                {{ $eventos->links() }}
            </div>
        @else
            <section class="glass-panel neon-outline p-10 text-center">
                <i class="bi bi-calendar2-x text-5xl text-cyan-200"></i>
                <h2 class="mt-5 text-2xl font-bold text-white">No hay eventos registrados</h2>
                <p class="mt-3 text-soft">Cuando el sistema tenga eventos, apareceran aqui con su contexto de operacion.</p>
            </section>
        @endif
    </div>
</x-app-layout>
