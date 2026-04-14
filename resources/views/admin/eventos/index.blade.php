<x-layouts.admin>
    <x-slot:title>Gestión de Eventos</x-slot:title>
    <x-slot:header>Gestión de Eventos</x-slot:header>

    <!-- Events Grid -->
    @if($eventos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($eventos as $evento)
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $evento->nombre }}</h3>
                    @if($evento->tiene_parqueadero)
                    <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Parqueadero
                    </span>
                    @endif
                </div>

                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($evento->descripcion, 100) }}</p>

                <div class="space-y-2 mb-4">
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

                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $evento->estado->nombre }}
                    </span>
                </div>

                <div class="flex gap-2 border-t border-slate-200 pt-4">
                    <a href="{{ route('admin.eventos.edit', $evento) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Editar
                    </a>
                    <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $eventos->links() }}
        </div>
    @else
        <div class="text-center py-12 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin eventos</h3>
            <p class="mt-1 text-sm text-gray-600">No hay eventos registrados aún</p>
            <div class="mt-6">
                <a href="{{ route('admin.eventos.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Crear evento
                </a>
            </div>
        </div>
    @endif
</x-layouts.admin>
