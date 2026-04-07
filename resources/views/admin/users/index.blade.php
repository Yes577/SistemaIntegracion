<x-layouts.admin>
    <x-slot:title>Usuarios</x-slot:title>
    <x-slot:header>Gestion de usuarios</x-slot:header>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Listado de usuarios</h3>
                <p class="mt-1 text-slate-600">Crea, edita o elimina usuarios del sistema.</p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="inline-flex rounded-xl bg-cyan-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400">
                Crear usuario
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="text-left text-sm text-slate-500">
                        <th class="py-3 pe-4">ID</th>
                        <th class="py-3 pe-4">Nombre</th>
                        <th class="py-3 pe-4">Email</th>
                        <th class="py-3 pe-4">Rol</th>
                        <th class="py-3 pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($users as $user)
                        <tr>
                            <td class="py-4 pe-4">{{ $user->id }}</td>
                            <td class="py-4 pe-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="py-4 pe-4">{{ $user->email }}</td>
                            <td class="py-4 pe-4">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase text-slate-700">
                                    {{ $user->tipoRol->nombre }}
                                </span>
                            </td>
                            <td class="py-4 pe-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-slate-300 px-3 py-2 font-medium text-slate-700 transition hover:bg-slate-100">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Seguro que deseas eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-300 px-3 py-2 font-medium text-rose-700 transition hover:bg-rose-50">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">No hay usuarios registrados.</td>
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
