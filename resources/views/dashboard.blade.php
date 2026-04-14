<x-app-layout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                ¡Bienvenido, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-2 text-gray-600">
                Gestiona tus eventos y parqueaderos desde aquí
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-600 text-sm">Total Eventos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalEventos }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Próximos Eventos</h2>
            </div>
            @if($eventosProximos->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($eventosProximos as $evento)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">{{ $evento->nombre }}</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $evento->fecha->format('d/m/Y') }} - {{ $evento->hora->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $evento->lugar }}</p>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $evento->estado->nombre }}
                                </span>
                            </div>
                            <a href="{{ route('eventos.show', $evento) }}" class="ml-4 text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Ver detalle →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-gray-600">No hay eventos próximos</p>
                    @if(auth()->user()->id_tipo_rol === 1)
                        <a href="{{ route('admin.eventos.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-900 font-medium">
                            Crear primer evento →
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
