<x-layouts.admin>
    <x-slot:title>Usuarios</x-slot:title>
    <x-slot:header>Gestion de usuarios</x-slot:header>

    <section class="glass-panel neon-outline p-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="section-eyebrow">User network</p>
                <h2 class="mt-4 text-4xl font-black text-white">Administra identidades del sistema.</h2>
                <p class="mt-3 text-soft">Crea, actualiza y controla los roles que operan la plataforma.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="theme-button-primary">
                <i class="bi bi-person-plus"></i>
                Crear usuario
            </a>
        </div>

        <div class="mt-8 overflow-x-auto">
            <table class="theme-table w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td class="font-semibold text-white">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="chip chip-accent">{{ $user->tipoRol->nombre }}</span></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="theme-button-secondary">Editar</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Seguro que deseas eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="theme-button-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-soft">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </section>
</x-layouts.admin>
