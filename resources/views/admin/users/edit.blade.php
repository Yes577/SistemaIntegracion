<x-layouts.admin>
    <x-slot:title>Editar Usuario</x-slot:title>
    <x-slot:header>Editar usuario</x-slot:header>

    <section class="glass-panel neon-outline p-8">
        <p class="section-eyebrow">User tuning</p>
        <h3 class="mt-4 text-4xl font-black text-white">Ajusta el perfil seleccionado.</h3>
        <p class="mt-3 text-soft">Modifica datos personales, rol y credenciales del usuario.</p>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-8">
            @method('PUT')
            @include('admin.users.form', ['submitLabel' => 'Actualizar usuario'])
        </form>
    </section>
</x-layouts.admin>
