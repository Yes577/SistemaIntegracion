<x-layouts.admin>
    <x-slot:title>Inscritos por Evento</x-slot:title>
    <x-slot:header>Control de inscripciones</x-slot:header>

    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8 text-center sm:text-left">
            <p class="section-eyebrow">Registry tower</p>
            <h3 class="mt-4 text-3xl font-black text-white">Seguimiento de asistentes y parqueaderos</h3>
            <p class="mt-3 text-soft">Visualiza en tiempo real quién se ha inscrito a tus eventos y el estado de sus reservas.</p>
        </section>

        @forelse($eventos as $evento)
            <section class="glass-panel neon-outline p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between border-b border-white/10 pb-6 mb-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="chip chip-accent">{{ $evento->estado->nombre }}</span>
                            <span class="text-soft text-xs uppercase tracking-widest font-bold">{{ $evento->fecha->format('d/m/Y') }}</span>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold text-white">{{ $evento->nombre }}</h2>
                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-soft text-sm">
                            <span><i class="bi bi-people me-1 text-cyan-200"></i>{{ $evento->capacidad_actual }} / {{ $evento->capacidad_maxima }} cupos</span>
                            @if($evento->tiene_parqueadero)
                                <span><i class="bi bi-p-circle me-1 text-violet-300"></i>Parqueadero activo</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-4xl font-black text-cyan-400">{{ $evento->inscripciones->count() }}</p>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-muted">Inscritos</p>
                        </div>
                    </div>
                </div>

                @if($evento->inscripciones->count() > 0)
                    <div class="overflow-x-auto -mx-8 px-8">
                        <table class="w-full text-left text-soft border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 text-[10px] uppercase tracking-[0.25em] text-muted">
                                    <th class="px-4 py-4 font-black">Usuario</th>
                                    <th class="px-4 py-4 font-black">Email</th>
                                    <th class="px-4 py-4 font-black">Complemento</th>
                                    <th class="px-4 py-4 font-black text-right">Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($evento->inscripciones as $inscripcion)
                                    <tr class="group hover:bg-white/[0.03] transition-all">
                                        <td class="px-4 py-4 text-white font-bold">{{ $inscripcion->user->name }}</td>
                                        <td class="px-4 py-4 text-sm">{{ $inscripcion->user->email }}</td>
                                        <td class="px-4 py-4">
                                            @if($inscripcion->id_parqueadero)
                                                <span class="chip chip-success text-[9px] font-black tracking-widest">
                                                    <i class="bi bi-p-circle me-1"></i> PARQUEADERO
                                                </span>
                                            @else
                                                <span class="text-muted/30 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-xs text-right text-muted tabular-nums">
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
                <div class="mx-auto w-20 h-20 rounded-full bg-cyan-400/10 flex items-center justify-center border border-cyan-400/20 mb-6">
                    <i class="bi bi-calendar-x text-4xl text-cyan-200"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Sin eventos disponibles</h2>
                <p class="mt-3 text-soft max-w-sm mx-auto">Cuando crees eventos, las listas de inscritos se generarán automáticamente en esta sección.</p>
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
