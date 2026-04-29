<nav id="sidebar"
     class="w-80 bg-zinc-300 dark:bg-zinc-900 border-r border-zinc-400 dark:border-zinc-800 flex flex-col h-screen overflow-hidden fixed lg:static inset-y-0 left-0 z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300">

    <div class="p-6">
        <div class="flex items-start justify-between mb-10">
            <a href="{{ route('users.index') }}" class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shrink-0">
                    <span class="text-white font-bold text-4xl">V</span>
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-3xl font-bold tracking-tighter text-gray-800 dark:text-white leading-none">SIRUSIR</h1>
                    <p class="text-[10px] text-orange-500 font-medium mt-1 uppercase">Sistem Informasi Rute Angkutan Pasir</p>
                </div>
            </a>

            <div id="notification-area" class="hidden lg:block relative">
                <button onclick="openNotificationModal()" class="relative p-2 text-zinc-800 dark:text-zinc-400 hover:text-orange-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notif-count" class="hidden absolute top-1 right-1 items-center justify-center h-5 w-5 text-[10px] font-bold text-white bg-red-600 rounded-full ring-2 ring-zinc-300 dark:ring-zinc-900">
                        0
                    </span>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-1 px-2">
            @if (auth()->check() && auth()->user()->role === 'admin')
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-800 dark:text-zinc-300 hover:text-black dark:hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    👥 Users
                </x-nav-link>
                <x-nav-link :href="route('armadas.index')" :active="request()->routeIs('armadas.*')" class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-800 dark:text-zinc-300 hover:text-black dark:hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    🚛 Armadas
                </x-nav-link>
                <x-nav-link :href="route('coordinates.index')" :active="request()->routeIs('coordinates.*')" class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-800 dark:text-zinc-300 hover:text-black dark:hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                    📍 Coordinates
                </x-nav-link>
            @endif

            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="nav-link-custom flex items-center gap-3 px-5 py-3.5 text-zinc-800 dark:text-zinc-300 hover:text-black dark:hover:text-white rounded-2xl text-lg transition-all"
                    active-class="bg-zinc-800 text-orange-400 font-semibold border-l-4 border-orange-500 !text-orange-400">
                📦 Orders
            </x-nav-link>
        </div>
    </div>

    <div class="mt-auto p-6 dark:border-t dark:border-zinc-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 text-red-500 hover:text-red-800 hover:bg-zinc-400 dark:hover:bg-zinc-800 rounded-2xl transition-all font-medium">
                <span class="text-xl">↩︎</span>
                <span>{{ __('Log Out') }}</span>
            </button>
        </form>
    </div>

    <button onclick="this.closest('nav').classList.add('-translate-x-full')"
            class="lg:hidden absolute top-6 right-6 text-zinc-800 dark:text-zinc-400 hover:text-white p-2">
        ✕
    </button>
</nav>