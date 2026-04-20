<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="section-eyebrow">Access point</p>
            <h1 class="mt-4 text-4xl font-black text-white">Iniciar sesion</h1>
            <p class="mt-3 text-soft">Entra al centro de control y consulta eventos, parqueaderos y paneles operativos.</p>
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

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="field-label">Correo electronico</label>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="field-label">Contrasena</label>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-soft">
                    <input id="remember_me" type="checkbox" name="remember" class="field-check">
                    Recordarme
                </label>
                <a href="{{ route('register') }}" class="theme-button-secondary">Crear cuenta</a>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-cyan-200 hover:text-white" href="{{ route('password.request') }}">Olvidaste tu contrasena?</a>
                @endif
                <button type="submit" class="theme-button-primary">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                    Ingresar
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
