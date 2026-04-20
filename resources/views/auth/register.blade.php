<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="section-eyebrow">Identity setup</p>
            <h1 class="mt-4 text-4xl font-black text-white">Crear cuenta</h1>
            <p class="mt-3 text-soft">Registra un nuevo acceso para entrar al ecosistema de eventos y parqueadero.</p>
        </div>

        @if ($errors->any())
            <div class="status-banner-danger">
                <p class="font-semibold">Corrige los siguientes errores:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="field-label">Nombre completo</label>
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="field-label">Correo electronico</label>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="field-label">Contrasena</label>
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="field-label">Confirmar contrasena</label>
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('login') }}" class="theme-button-secondary">Volver a iniciar sesion</a>
                <button type="submit" class="theme-button-primary">
                    <i class="bi bi-person-check"></i>
                    Registrarme
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
