<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>VRP-GIS Angkutan | Optimalisasi Rute Pengangkutan</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        @vite(['resources/css/app.css'])
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100">

        <div class="flex h-screen overflow-hidden">

            <!-- Sidebar (Mobile Responsive) -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Top Header (Mobile) -->
                <header class="bg-zinc-900 border-b border-zinc-800 px-4 sm:px-6 py-4 flex items-center justify-between lg:hidden">
                    <div class="flex items-center gap-3">
                        <button id="mobile-menu-button"
                                class="p-2 text-zinc-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">V</span>
                            </div>
                            <span class="font-semibold tracking-tight">VRP-GIS</span>
                        </div>
                    </div>
                </header>

                <!-- Desktop Page Header -->
                @isset($header)
                    <header class="hidden lg:block bg-zinc-900 border-b border-zinc-800 px-6 py-5">
                        {{ $header }}
                    </header>
                @endisset

                <!-- Main Content -->
                <main class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8 bg-zinc-950">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @vite(['resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
        @stack('scripts')

        <!-- Mobile Menu Script -->
        <script>
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const sidebar = document.querySelector('nav');
                sidebar.classList.toggle('-translate-x-full');
            });
        </script>
    </body>
</html>