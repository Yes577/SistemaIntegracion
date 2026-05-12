<x-layouts.admin>
    <x-slot:title>Reporte del Evento</x-slot:title>
    <x-slot:header>Reporte operativo</x-slot:header>

    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="section-eyebrow">Event report</p>
                    <h1 class="mt-4 text-4xl font-black text-white">{{ $evento->nombre }}</h1>
                    <p class="mt-3 text-soft">Consolidado de inscripciones, asistencia y accesos para el evento seleccionado.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.eventos.checkin', $evento) }}" class="theme-button-primary">
                        <i class="bi bi-qr-code-scan"></i>
                        Abrir escaner
                    </a>
                    <form action="{{ route('admin.eventos.reminders.store', $evento) }}" method="POST">
                        @csrf
                        <button type="submit" class="theme-button-secondary">
                            <i class="bi bi-envelope"></i>
                            Enviar recordatorios
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-3">
            <article class="metric-card">
                <p class="chip chip-accent">Inscritos</p>
                <p class="metric-value">{{ $inscritos_totales }}</p>
                <p class="metric-label">Usuarios registrados para este evento.</p>
            </article>
            <article class="metric-card">
                <p class="chip chip-success">Asistentes</p>
                <p class="metric-value">{{ $asistentes_confirmados }}</p>
                <p class="metric-label">Check-ins confirmados mediante QR.</p>
            </article>
            <article class="metric-card">
                <p class="chip chip-warning">Asistencia</p>
                <p class="metric-value">{{ $porcentaje_asistencia }}%</p>
                <p class="metric-label">{{ $pendientes_check_in }} pendientes de registrar.</p>
            </article>
        </section>

        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 border-b border-white/10 pb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Detalle de inscripciones</h2>
                    <p class="mt-2 text-soft">Estado actual de cada asistente, con soporte para control de ingreso y trazabilidad.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-sm text-soft">
                    <span class="chip">{{ $evento->fecha->format('d/m/Y') }}</span>
                    <span class="chip">{{ $evento->hora->format('H:i') }}</span>
                    <span class="chip">{{ $evento->lugar }}</span>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="theme-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Parqueadero</th>
                            <th class="text-right">Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evento->inscripciones as $inscripcion)
                            <tr>
                                <td class="font-bold text-white">{{ $inscripcion->user->name }}</td>
                                <td>{{ $inscripcion->user->email }}</td>
                                <td>
                                    @if($inscripcion->estado_check_in === \App\Models\Inscripcion::STATUS_CONFIRMED)
                                        <span class="chip chip-success">Confirmado</span>
                                    @elseif($inscripcion->isQrExpired())
                                        <span class="chip chip-warning">Expirado</span>
                                    @else
                                        <span class="chip">Pendiente</span>
                                    @endif
                                </td>
                                <td>{{ $inscripcion->id_parqueadero ? 'Si' : 'No' }}</td>
                                <td class="text-right text-sm">
                                    @if($inscripcion->check_in_at)
                                        <span class="text-white">{{ $inscripcion->check_in_at->format('d/m/Y H:i') }}</span>
                                    @else
                                        <span class="text-muted">Sin registrar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-soft">No hay inscripciones registradas para este evento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
