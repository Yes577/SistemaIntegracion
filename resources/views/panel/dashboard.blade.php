<x-layouts.user>
    <x-slot:title>Dashboard Usuario</x-slot:title>
    <x-slot:header>Panel de movilidad</x-slot:header>

    <div class="space-y-6">
        

    

        <section class="grid gap-4 md:grid-cols-2">
            <a href="{{ route('eventos.index') }}" class="event-card">
                <i class="bi bi-calendar2-week text-3xl text-cyan-200"></i>
                <h3 class="mt-5 text-2xl font-bold text-white">Explorar eventos</h3>
                <p class="mt-3 text-soft">Consulta fechas, lugares, estados y si cada evento tiene parqueadero asociado.</p>
            </a>
            <a href="{{ route('profile.edit') }}" class="event-card">
                <i class="bi bi-person-gear text-3xl text-amber-200"></i>
                <h3 class="mt-5 text-2xl font-bold text-white">Configurar perfil</h3>
                <p class="mt-3 text-soft">Ajusta tus datos y asegura el acceso a la plataforma desde el modulo personal.</p>
            </a>
        </section>
    </div>
</x-layouts.user>
