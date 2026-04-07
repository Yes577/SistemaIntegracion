<x-layouts.admin>
    <x-slot:title>Editar Usuario</x-slot:title>
    <x-slot:header>Editar usuario</x-slot:header>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-xl font-semibold text-slate-900">Editar usuario</h3>
        <p class="mt-1 text-slate-600">Actualiza los datos del usuario seleccionado.</p>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6">
            @method('PUT')
            @include('admin.users.form', ['submitLabel' => 'Actualizar usuario'])
        </form>
    </section>
</x-layouts.admin>
