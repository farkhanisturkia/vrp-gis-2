<nav x-data="{ open: false }" 
     class="w-72 bg-zinc-900 border-r border-zinc-800 flex flex-col h-screen overflow-hidden">

    <div class="p-6">
        <!-- Logo -->
        <a href="{{ route('users.index') }}" 
           class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-4xl">V</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tighter text-white">VRP-GIS</h1>
                <p class="text-xs text-orange-400 font-medium">ANGKUTAN PT</p>
            </div>
        </a>

        <!-- Navigation Menu -->
        <div class="flex flex-col gap-1 px-3">
            @if (auth()->check() && auth()->user()->role === 'admin')
                
                <x-nav-link 
                    :href="route('users.index')" 
                    :active="request()->routeIs('users.*')"
                    class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-300 hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    👥 Users
                </x-nav-link>

                <x-nav-link 
                    :href="route('armadas.index')" 
                    :active="request()->routeIs('armadas.*')"
                    class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-300 hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    🚛 Armadas
                </x-nav-link>

                <x-nav-link 
                    :href="route('coordinates.index')" 
                    :active="request()->routeIs('coordinates.*')"
                    class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-300 hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    📍 Coordinates
                </x-nav-link>
            @endif

            <x-nav-link 
                :href="route('orders.index')" 
                :active="request()->routeIs('orders.*')"
                class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-300 hover:text-white rounded-2xl text-lg transition-all"
                active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                📦 Orders
            </x-nav-link>
        </div>
    </div>

    <!-- Logout -->
    <div class="mt-auto p-6 border-t border-zinc-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 text-red-400 hover:text-red-500 hover:bg-zinc-800 rounded-2xl transition-all font-medium">
                <span class="text-xl">↩︎</span>
                <span>{{ __('Log Out') }}</span>
            </button>
        </form>
    </div>
</nav>