<x-app-layout>
    <div class="space-y-6">
        <section class="glass-panel neon-outline p-8">
            <p class="section-eyebrow">Profile deck</p>
            <h1 class="mt-4 text-4xl font-black text-white">Ajustes de perfil y seguridad</h1>
            <p class="mt-3 text-soft">Actualiza tus datos, refuerza tu contrasena y controla el acceso de tu cuenta.</p>
        </section>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="glass-panel neon-outline p-6 xl:col-span-1">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="glass-panel neon-outline p-6 xl:col-span-1">
                @include('profile.partials.update-password-form')
            </div>
            <div class="glass-panel neon-outline p-6 xl:col-span-1">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
