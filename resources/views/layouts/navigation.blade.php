<nav x-data="{ open: false }" class="glass-panel neon-outline px-4 py-4 sm:px-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-400/12 text-cyan-200">
                    <i class="bi bi-car-front-fill text-xl"></i>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-200">Mobility core</p>
                    <p class="text-lg font-bold text-white">Eventos y parqueadero</p>
                </div>
            </a>

            <button @click="open = ! open" class="topbar-link sm:hidden">
                <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
            </button>
        </div>

        <div :class="open ? 'flex' : 'hidden'" class="hidden flex-col gap-3 sm:flex sm:flex-row sm:flex-wrap sm:items-center">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
            <x-nav-link :href="route('eventos.index')" :active="request()->routeIs('eventos.*')">{{ __('Eventos') }}</x-nav-link>
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">{{ __('Profile') }}</x-nav-link>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="topbar-link">
                        <span>{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>
