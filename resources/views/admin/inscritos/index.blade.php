<x-layouts.admin>
    <x-slot:title>Inscritos por Evento</x-slot:title>
    <x-slot:header>Control de inscripciones</x-slot:header>

    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8 text-center sm:text-left">
            <p class="section-eyebrow">Registry tower</p>
            <h3 class="mt-4 text-3xl font-black text-white">Seguimiento de asistentes, check-in y recordatorios</h3>
            <p class="mt-3 text-soft">Visualiza quien se ha inscrito, quien ya asistio y accede al escaner QR por evento.</p>
        </section>

        @forelse($eventos as $evento)
            @php
                $asistentesConfirmados = $evento->inscripciones->where('estado_check_in', \App\Models\Inscripcion::STATUS_CONFIRMED)->count();
                $porcentajeAsistencia = $evento->inscripciones->count() > 0
                    ? round(($asistentesConfirmados / $evento->inscripciones->count()) * 100, 1)
                    : 0;
            @endphp
            <section class="glass-panel neon-outline p-8">
                <div class="flex flex-col gap-4 border-b border-white/10 pb-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="chip chip-accent">{{ $evento->estado->nombre }}</span>
                            <span class="text-soft text-xs uppercase tracking-widest font-bold">{{ $evento->fecha->format('d/m/Y') }}</span>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold text-white">{{ $evento->nombre }}</h2>
                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-soft text-sm">
                            <span><i class="bi bi-people me-1 text-cyan-200"></i>{{ $evento->inscripciones->count() }} inscritos</span>
                            <span><i class="bi bi-check2-circle me-1 text-emerald-200"></i>{{ $asistentesConfirmados }} asistentes</span>
                            <span><i class="bi bi-percent me-1 text-amber-200"></i>{{ $porcentajeAsistencia }}% asistencia</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.eventos.report', $evento) }}" class="theme-button-secondary">
                            <i class="bi bi-bar-chart"></i>
                            Ver reporte
                        </a>
                        <a href="{{ route('admin.eventos.checkin', $evento) }}" class="theme-button-primary">
                            <i class="bi bi-qr-code-scan"></i>
                            Abrir escaner
                        </a>
                    </div>
                </div>

                @if($evento->inscripciones->count() > 0)
                    <div class="mt-6 overflow-x-auto">
                        <table class="theme-table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Complemento</th>
                                    <th>Estado check-in</th>
                                    <th class="text-right">Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evento->inscripciones as $inscripcion)
                                    <tr>
                                        <td class="font-bold text-white">{{ $inscripcion->user->name }}</td>
                                        <td>{{ $inscripcion->user->email }}</td>
                                        <td>
                                            @if($inscripcion->id_parqueadero)
                                                <span class="chip chip-success text-[9px] font-black tracking-widest">
                                                    <i class="bi bi-p-circle me-1"></i> PARQUEADERO
                                                </span>
                                            @else
                                                <span class="text-muted text-xs">Sin parqueadero</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inscripcion->estado_check_in === \App\Models\Inscripcion::STATUS_CONFIRMED)
                                                <span class="chip chip-success">Confirmado</span>
                                            @elseif($inscripcion->isQrExpired())
                                                <span class="chip chip-warning">Expirado</span>
                                            @else
                                                <span class="chip">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="text-right text-xs text-muted tabular-nums">
                                            {{ $inscripcion->created_at->format('d/m/Y') }}<br>
                                            <span class="opacity-50">{{ $inscripcion->created_at->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-12 text-center rounded-[1.6rem] bg-white/[0.02] border border-dashed border-white/10">
                        <i class="bi bi-person-slash text-4xl text-white/10"></i>
                        <p class="mt-4 text-soft text-sm">No se registran inscripciones para este evento.</p>
                    </div>
                @endif
            </section>
        @empty
            <section class="glass-panel neon-outline p-16 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-cyan-400/20 bg-cyan-400/10">
                    <i class="bi bi-calendar-x text-4xl text-cyan-200"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Sin eventos disponibles</h2>
                <p class="mt-3 text-soft max-w-sm mx-auto">Cuando crees eventos, las listas de inscritos y la operacion de check-in apareceran aqui.</p>
                <div class="mt-8">
                    <a href="{{ route('admin.eventos.create') }}" class="theme-button-primary">
                        <i class="bi bi-plus-lg"></i>
                        Crear primer evento
                    </a>
                </div>
            </section>
        @endforelse
    </div>
</x-layouts.admin>
