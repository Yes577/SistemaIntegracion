<x-app-layout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('eventos.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                ← Volver a eventos
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $evento->nombre }}</h1>
                                <p class="mt-1 text-gray-600">{{ $evento->descripcion }}</p>
                            </div>
                            @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('eventos.edit', $evento) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Editar
                                </a>
                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-gray-50">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Fecha</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->fecha->format('d de F de Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Hora</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->hora->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Lugar</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->lugar }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Capacidad Máxima</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->capacidad_maxima }} personas</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Estado</p>
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $evento->estado->nombre }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Parqueadero</p>
                                <p class="mt-1 text-lg text-gray-900">
                                    @if($evento->tiene_parqueadero)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-green-100 text-green-800">
                                            Sí
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-gray-100 text-gray-800">
                                            No
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Creado por</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->usuario->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Creado el</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parqueadero Section -->
                @if($evento->tiene_parqueadero && $evento->parqueadero)
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Información del Parqueadero</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Capacidad Total</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->parqueadero->capacidad_total }} espacios</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Cupos Disponibles</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $evento->parqueadero->cupos_disponibles }} espacios</p>
                            </div>
                            @if($evento->parqueadero->descripcion)
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-600">Descripción</p>
                                <p class="mt-1 text-gray-900">{{ $evento->parqueadero->descripcion }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Identificador</dt>
                            <dd class="text-sm text-gray-900 mt-1">#{{ $evento->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Estado</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $evento->estado->nombre }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Organizador</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $evento->usuario->name }}</dd>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-600">Última actualización</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $evento->updated_at->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
