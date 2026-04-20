<x-layouts.admin>
    <x-slot:title>Editar Evento</x-slot:title>
    <x-slot:header>Editar evento</x-slot:header>

    <section class="glass-panel neon-outline p-8">
        <div class="mb-8">
            <p class="section-eyebrow">Event tuning</p>
            <h2 class="mt-4 text-4xl font-black text-white">Ajusta la activacion seleccionada.</h2>
            <p class="mt-3 max-w-3xl text-soft">Actualiza datos del evento y su parqueadero desde una sola vista de configuracion.</p>
        </div>

        <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="nombre" class="field-label">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $evento->nombre) }}" required class="field-input">
                    @error('nombre')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="descripcion" class="field-label">Descripcion</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="field-textarea">{{ old('descripcion', $evento->descripcion) }}</textarea>
                    @error('descripcion')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="fecha" class="field-label">Fecha *</label>
                    <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $evento->fecha->format('Y-m-d')) }}" required class="field-input">
                    @error('fecha')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="hora" class="field-label">Hora *</label>
                    <input type="time" name="hora" id="hora" value="{{ old('hora', $evento->hora->format('H:i')) }}" required class="field-input">
                    @error('hora')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="lugar" class="field-label">Lugar *</label>
                    <input type="text" name="lugar" id="lugar" value="{{ old('lugar', $evento->lugar) }}" required class="field-input">
                    @error('lugar')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="capacidad_maxima" class="field-label">Capacidad maxima *</label>
                    <input type="number" name="capacidad_maxima" id="capacidad_maxima" value="{{ old('capacidad_maxima', $evento->capacidad_maxima) }}" min="1" required class="field-input">
                    @error('capacidad_maxima')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="id_estado" class="field-label">Estado *</label>
                    <select name="id_estado" id="id_estado" required class="field-select">
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ old('id_estado', $evento->id_estado) == $estado->id ? 'selected' : '' }}>{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_estado')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="rounded-[1.8rem] border border-cyan-400/15 bg-cyan-400/5 p-6">
                <label for="tiene_parqueadero" class="flex items-center gap-3 text-white">
                    <input type="checkbox" name="tiene_parqueadero" id="tiene_parqueadero" value="1" {{ old('tiene_parqueadero', $evento->tiene_parqueadero) ? 'checked' : '' }} onchange="toggleParqueaderoFields()" class="field-check">
                    <span class="text-base font-semibold">Este evento tiene parqueadero habilitado</span>
                </label>

                <div id="parqueadero-fields" class="mt-6 grid gap-6 md:grid-cols-2 {{ old('tiene_parqueadero', $evento->tiene_parqueadero) ? '' : 'hidden' }}">
                    <div>
                        <label for="parqueadero_capacidad" class="field-label">Capacidad total *</label>
                        <input type="number" name="parqueadero_capacidad" id="parqueadero_capacidad" value="{{ old('parqueadero_capacidad', $evento->parqueadero?->capacidad_total ?? '') }}" min="1" class="field-input">
                        @error('parqueadero_capacidad')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="parqueadero_cupos" class="field-label">Cupos disponibles *</label>
                        <input type="number" name="parqueadero_cupos" id="parqueadero_cupos" value="{{ old('parqueadero_cupos', $evento->parqueadero?->cupos_disponibles ?? '') }}" min="0" class="field-input">
                        @error('parqueadero_cupos')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="parqueadero_descripcion" class="field-label">Descripcion del parqueadero</label>
                        <textarea name="parqueadero_descripcion" id="parqueadero_descripcion" rows="3" class="field-textarea">{{ old('parqueadero_descripcion', $evento->parqueadero?->descripcion ?? '') }}</textarea>
                        @error('parqueadero_descripcion')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 border-t border-white/10 pt-6">
                <a href="{{ route('admin.eventos.index') }}" class="theme-button-secondary">Cancelar</a>
                <button type="submit" class="theme-button-primary">
                    <i class="bi bi-check2-circle"></i>
                    Actualizar evento
                </button>
            </div>
        </form>
    </section>

    <script>
        function toggleParqueaderoFields() {
            const checkbox = document.getElementById('tiene_parqueadero');
            const fields = document.getElementById('parqueadero-fields');
            fields.classList.toggle('hidden', !checkbox.checked);
        }
    </script>
</x-layouts.admin>
