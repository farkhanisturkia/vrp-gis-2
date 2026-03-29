<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'VRP-GIS Angkutan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-950 text-white font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        
        <!-- Background subtle effect -->
        <div class="absolute inset-0 bg-[radial-gradient(at_top_right,#1e40af_0%,transparent_60%)] opacity-30"></div>
        <div class="absolute inset-0 bg-[radial-gradient(at_bottom_left,#c2410c_0%,transparent_70%)] opacity-20"></div>

        <div class="w-full max-w-md relative z-10">
            
            <!-- Logo & Brand -->
            <div class="flex flex-col items-center mb-10">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <span class="text-white font-bold text-4xl tracking-tighter">V</span>
                    </div>
                    <div class="leading-none">
                        <h1 class="text-4xl font-semibold tracking-tighter text-white">VRP-GIS</h1>
                        <p class="text-orange-400 text-sm font-medium tracking-widest">ANGKUTAN PT</p>
                    </div>
                </div>
                
                <div class="text-center mt-2">
                    <p class="text-zinc-400 text-lg">
                        Sistem Optimalisasi Rute &amp; GIS
                    </p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-zinc-900 border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-zinc-500 text-xs">
                    © 2026 PT Angkutan Indonesia • VRP-GIS Platform
                </p>
            </div>
        </div>
    </div>
</body>
</html>