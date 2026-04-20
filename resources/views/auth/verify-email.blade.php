<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="section-eyebrow">Email checkpoint</p>
            <h1 class="mt-4 text-4xl font-black text-white">Verifica tu correo</h1>
            <p class="mt-3 text-soft">Antes de continuar, confirma tu direccion de correo con el enlace enviado.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="status-banner-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="theme-button-secondary">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>
