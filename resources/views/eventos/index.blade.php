<x-app-layout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Eventos</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Lista de todos los eventos del sistema
                </p>
            </div>
        </div>

        <!-- Events Grid -->
        @if($eventos->count() > 0)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventos as $evento)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $evento->nombre }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($evento->descripcion, 100) }}</p>
                            </div>
                            @if($evento->tiene_parqueadero)
                            <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                P
                            </span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $evento->fecha->format('d/m/Y') }} - {{ $evento->hora->format('H:i') }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $evento->lugar }}
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $evento->estado->nombre }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('eventos.show', $evento) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Ver
                                </a>
                                @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
                                <a href="{{ route('admin.eventos.edit', $evento) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Editar
                                </a>
                                <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $eventos->links() }}
            </div>
        @else
            <div class="mt-8 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Sin eventos</h3>
                <p class="mt-1 text-sm text-gray-600">No hay eventos registrados aún</p>
                @if(auth()->user() && auth()->user()->id_tipo_rol === 1)
                <div class="mt-6">
                    <a href="{{ route('admin.eventos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear evento
                    </a>
                </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
