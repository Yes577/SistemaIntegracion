<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="section-eyebrow">Secure checkpoint</p>
            <h1 class="mt-4 text-4xl font-black text-white">Confirmar contrasena</h1>
            <p class="mt-3 text-soft">Este tramo requiere validar nuevamente tu identidad antes de continuar.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>{{ __('Confirm') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
