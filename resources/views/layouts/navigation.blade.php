<nav x-data="{ open: false }" class="flex flex-col h-svh bg-white border-r border-gray-100">
    <div class="w-[250px] px-4 py-7">
        <div class="flex flex-col gap-9">
            <a class="flex h-fit justify-center items-center gap-3" href="{{ route('users.index') }}">
                <x-application-logo class="block h-12 w-auto fill-current text-gray-900" />
                <p class="font-extrabold text-3xl">VRP-G1</p>
            </a>

            <!-- Navigation Links -->
            <div class="flex flex-col gap-5">
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <div class="hidden space-x-8   sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('users.index')" class="text-xl" :active="request()->routeIs('users.index')">
                            {{ __('users') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('armadas.index')" class="text-xl" :active="request()->routeIs('armadas.index')">
                            {{ __('armadas') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('coordinates.index')" class="text-xl" :active="request()->routeIs('coordinates.index')">
                            {{ __('coordinates') }}
                        </x-nav-link>
                    </div>
                @endif
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('orders.index')" class="text-xl" :active="request()->routeIs('orders.index')">
                        {{ __('orders') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-auto px-4 py-6 flex justify-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="font-extrabold text-xl text-red-700 hover:text-red-900" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</nav>