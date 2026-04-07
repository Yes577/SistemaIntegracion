<x-layouts.admin>
    <x-slot:title>Crear Usuario</x-slot:title>
    <x-slot:header>Crear usuario</x-slot:header>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-xl font-semibold text-slate-900">Nuevo usuario</h3>
        <p class="mt-1 text-slate-600">Completa la informacion y asigna el rol correspondiente.</p>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6">
            @include('admin.users.form', ['submitLabel' => 'Guardar usuario'])
        </form>
    </section>
</x-layouts.admin>
