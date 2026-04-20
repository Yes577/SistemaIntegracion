<x-layouts.admin>
    <x-slot:title>Crear Usuario</x-slot:title>
    <x-slot:header>Crear usuario</x-slot:header>

    <section class="glass-panel neon-outline p-8">
        <p class="section-eyebrow">User onboarding</p>
        <h3 class="mt-4 text-4xl font-black text-white">Nuevo usuario para la red.</h3>
        <p class="mt-3 text-soft">Completa la informacion base y define el rol dentro del sistema.</p>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-8">
            @include('admin.users.form', ['submitLabel' => 'Guardar usuario'])
        </form>
    </section>
</x-layouts.admin>
