<x-layouts.admin>
    <x-slot:title>Editar Evento</x-slot:title>
    <x-slot:header>Editar Evento</x-slot:header>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="nombre"
                        value="{{ old('nombre', $evento->nombre) }}"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                    >
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea 
                        name="descripcion" 
                        id="descripcion"
                        rows="4"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                    >{{ old('descripcion', $evento->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                    <input 
                        type="date" 
                        name="fecha" 
                        id="fecha"
                        value="{{ old('fecha', $evento->fecha->format('Y-m-d')) }}"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('fecha') border-red-500 @enderror"
                    >
                    @error('fecha')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora -->
                <div>
                    <label for="hora" class="block text-sm font-medium text-gray-700">Hora *</label>
                    <input 
                        type="time" 
                        name="hora" 
                        id="hora"
                        value="{{ old('hora', $evento->hora->format('H:i')) }}"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('hora') border-red-500 @enderror"
                    >
                    @error('hora')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lugar -->
                <div class="md:col-span-2">
                    <label for="lugar" class="block text-sm font-medium text-gray-700">Lugar *</label>
                    <input 
                        type="text" 
                        name="lugar" 
                        id="lugar"
                        value="{{ old('lugar', $evento->lugar) }}"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('lugar') border-red-500 @enderror"
                    >
                    @error('lugar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidad Máxima -->
                <div>
                    <label for="capacidad_maxima" class="block text-sm font-medium text-gray-700">Capacidad Máxima *</label>
                    <input 
                        type="number" 
                        name="capacidad_maxima" 
                        id="capacidad_maxima"
                        value="{{ old('capacidad_maxima', $evento->capacidad_maxima) }}"
                        min="1"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('capacidad_maxima') border-red-500 @enderror"
                    >
                    @error('capacidad_maxima')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="id_estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                    <select 
                        name="id_estado" 
                        id="id_estado"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('id_estado') border-red-500 @enderror"
                    >
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ old('id_estado', $evento->id_estado) == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parqueadero Checkbox -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="tiene_parqueadero" 
                            id="tiene_parqueadero"
                            value="1"
                            {{ old('tiene_parqueadero', $evento->tiene_parqueadero) ? 'checked' : '' }}
                            onchange="toggleParqueaderoFields()"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="tiene_parqueadero" class="ml-2 block text-sm text-gray-700">
                            ¿Este evento tiene parqueadero?
                        </label>
                    </div>
                </div>

                <!-- Parqueadero Fields -->
                <div id="parqueadero-fields" class="md:col-span-2 space-y-6" style="display: {{ old('tiene_parqueadero', $evento->tiene_parqueadero) ? 'block' : 'none' }};">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-blue-900 mb-4">Detalles del Parqueadero</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Capacidad Total -->
                            <div>
                                <label for="parqueadero_capacidad" class="block text-sm font-medium text-gray-700">Capacidad Total *</label>
                                <input 
                                    type="number" 
                                    name="parqueadero_capacidad" 
                                    id="parqueadero_capacidad"
                                    value="{{ old('parqueadero_capacidad', $evento->parqueadero?->capacidad_total ?? '') }}"
                                    min="1"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('parqueadero_capacidad') border-red-500 @enderror"
                                >
                                @error('parqueadero_capacidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cupos Disponibles -->
                            <div>
                                <label for="parqueadero_cupos" class="block text-sm font-medium text-gray-700">Cupos Disponibles *</label>
                                <input 
                                    type="number" 
                                    name="parqueadero_cupos" 
                                    id="parqueadero_cupos"
                                    value="{{ old('parqueadero_cupos', $evento->parqueadero?->cupos_disponibles ?? '') }}"
                                    min="0"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('parqueadero_cupos') border-red-500 @enderror"
                                >
                                @error('parqueadero_cupos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="parqueadero_descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea 
                                    name="parqueadero_descripcion" 
                                    id="parqueadero_descripcion"
                                    rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('parqueadero_descripcion') border-red-500 @enderror"
                                >{{ old('parqueadero_descripcion', $evento->parqueadero?->descripcion ?? '') }}</textarea>
                                @error('parqueadero_descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.eventos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition"
                >
                    Actualizar Evento
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleParqueaderoFields() {
            const checkbox = document.getElementById('tiene_parqueadero');
            const fields = document.getElementById('parqueadero-fields');
            fields.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
</x-layouts.admin>
