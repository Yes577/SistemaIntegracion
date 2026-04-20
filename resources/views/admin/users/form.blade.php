@csrf

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="field-label">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name', isset($user) ? $user->name : '') }}" required class="field-input">
        @error('name')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="field-label">Correo electronico</label>
        <input id="email" name="email" type="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required class="field-input">
        @error('email')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="id_tipo_rol" class="field-label">Rol</label>
        <select id="id_tipo_rol" name="id_tipo_rol" required class="field-select">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('id_tipo_rol', isset($user) ? $user->id_tipo_rol : '') == $role->id)>
                    {{ strtoupper($role->nombre) }}
                </option>
            @endforeach
        </select>
        @error('id_tipo_rol')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="field-label">Password {{ isset($user) ? '(dejar vacio para conservar)' : '' }}</label>
        <input id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }} class="field-input">
        @error('password')
            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="password_confirmation" class="field-label">Confirmar password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" {{ isset($user) ? '' : 'required' }} class="field-input">
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="theme-button-primary">{{ $submitLabel }}</button>
    <a href="{{ route('admin.users.index') }}" class="theme-button-secondary">Cancelar</a>
</div>
