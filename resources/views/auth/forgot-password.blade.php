<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="section-eyebrow">Recovery lane</p>
            <h1 class="mt-4 text-4xl font-black text-white">Recuperar acceso</h1>
            <p class="mt-3 text-soft">Ingresa tu correo y te enviaremos un enlace para restaurar tu acceso al sistema.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
