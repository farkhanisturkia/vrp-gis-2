<x-guest-layout>
    <div class="p-8 sm:p-10">
        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-7">
                <x-input-label for="email" :value="__('Email')" class="text-zinc-800 dark:text-zinc-300 text-sm font-medium" />
                <x-text-input 
                    id="email" 
                    class="block mt-2 w-full bg-zinc-300 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 placeholder-zinc-500" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="email" 
                    placeholder="email@example.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-7">
                <x-input-label for="password" :value="__('Password')" class="text-zinc-800 dark:text-zinc-300 text-sm font-medium" />
                <x-text-input 
                    id="password" 
                    class="block mt-2 w-full bg-zinc-300 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 placeholder-zinc-500" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Login Button -->
            <x-primary-button class="w-full py-4 text-base text-zinc-950 dark:text-zinc-300 font-semibold 
                                bg-orange-500 hover:bg-orange-600 active:bg-orange-700 
                                transition-all duration-300 rounded-2xl shadow-lg shadow-orange-500/30 
                                flex items-center justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>