@csrf

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        @error('name')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Correo electronico</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        @error('email')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="id_tipo_rol" class="mb-2 block text-sm font-medium text-slate-700">Rol</label>
        <select id="id_tipo_rol" name="id_tipo_rol" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('id_tipo_rol', $user->id_tipo_rol ?? '') == $role->id)>
                    {{ strtoupper($role->nombre) }}
                </option>
            @endforeach
        </select>
        @error('id_tipo_rol')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
            Password {{ isset($user) ? '(dejar vacio para conservar)' : '' }}
        </label>
        <input id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }} class="w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        @error('password')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirmar password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" {{ isset($user) ? '' : 'required' }} class="w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white transition hover:bg-slate-700">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">
        Cancelar
    </a>
</div>
