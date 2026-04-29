<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <title>{{ config('app.name', 'Sistem Informasi Rute Angkutan Pasir (SIRUSIR)') }}</title>
   
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|instrument-sans:400,500,600" rel="stylesheet" />
   
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                content: [],
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'system-ui', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="bg-slate-300 dark:bg-zinc-950 text-gray-950 dark:text-white min-h-screen flex flex-col overflow-x-hidden">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(at_top_right,#1E3A8A_0%,transparent_60%)] opacity-40"></div>
   
    <div class="relative flex-1 w-full max-w-7xl mx-auto px-5 sm:px-6 py-8 md:py-12">
        
        <!-- Header -->
        <header class="flex justify-between items-center mb-12 md:mb-16">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-orange-500 rounded-2xl flex items-center justify-center text-white font-bold text-2xl">V</div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">SIRUSIR</h1>
                    <p class="text-xs text-orange-400 -mt-1">Sistem Informasi Rute Angkutan Pasir</p>
                </div>
            </div>
           
            @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/users') }}"
                           class="px-5 sm:px-6 py-2.5 sm:py-3 bg-white text-zinc-900 hover:bg-orange-500 hover:text-white font-medium rounded-2xl transition-all duration-300 flex items-center gap-2 text-sm sm:text-base">
                            <span>Masuk ke Dashboard</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 sm:px-6 py-2.5 sm:py-3 border border-zinc-700 hover:border-white/50 font-medium rounded-2xl transition-all text-sm sm:text-base">
                            Masuk
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            
            <!-- Left Content -->
            <div class="space-y-8 md:space-y-10">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-3xl text-sm">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="font-medium">Sistem Optimalisasi Rute Real-time</span>
                </div>
                
                <h1 class="text-[2.75rem] leading-none sm:text-6xl md:text-7xl font-bold tracking-tighter">
                    Rute Terbaik.<br>
                    <span class="text-orange-500">Pengiriman Efisien.</span>
                </h1>
                
                <p class="text-lg md:text-xl text-zinc-800 dark:text-zinc-400 max-w-md lg:max-w-lg">
                    Platform SIRUSIR terintegrasi untuk manajemen armada angkutan PT. 
                    Optimasi rute, visualisasi peta, dan analisis performa dalam satu sistem.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login') }}"
                       class="px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-3 text-base sm:text-lg">
                        Mulai Sekarang
                        <span aria-hidden="true">→</span>
                    </a>
                   
                    <a href="#"
                       class="px-8 py-4 border border-black/90 dark:border-white/30 hover:border-white/60 font-medium rounded-2xl transition-all flex items-center justify-center gap-3 text-base sm:text-lg">
                        Lihat Demo
                    </a>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6">
                    <div class="flex gap-4">
                        <div class="text-orange-500 mt-1 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314-11.314z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold">VRP Intelligence</h4>
                            <p class="text-sm text-zinc-800 dark:text-zinc-400">Algoritma Vehicle Routing Problem canggih</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-orange-500 mt-1 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2 2 2 0 01-2-2 2 2 0 01-2-2 2 2 0 012-2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold">GIS Visualization</h4>
                            <p class="text-sm text-zinc-800 dark:text-zinc-400">Peta interaktif + heatmap rute</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="relative mt-8 lg:mt-0">
                <div class="bg-zinc-900/70 backdrop-blur-xl border border-white/10 rounded-3xl p-3 shadow-2xl">
                    <div class="aspect-video bg-zinc-950 rounded-2xl overflow-hidden relative">
                        <!-- Mock Peta -->
                        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/800/450')] bg-cover bg-center"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/60 to-transparent"></div>
                       
                        <!-- Rute Lines -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 800 450">
                            <path d="M150 300 Q400 150 650 280" fill="none" stroke="#f97316" stroke-width="5" stroke-opacity="0.75" stroke-dasharray="10 6"/>
                            <circle cx="150" cy="300" r="13" fill="#f97316"/>
                            <circle cx="650" cy="280" r="13" fill="#f97316"/>
                        </svg>
                        
                        <!-- Bottom Stats -->
                        <div class="absolute bottom-4 sm:bottom-6 left-4 sm:left-6 bg-black/70 backdrop-blur-md px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl text-sm">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <div class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-xl text-xs font-medium">12 Kendaraan</div>
                                <div class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-xl text-xs font-medium">98% Efisiensi</div>
                            </div>
                        </div>
                        
                        <!-- Top Stats -->
                        <div class="absolute top-4 sm:top-6 right-4 sm:right-6 bg-black/70 backdrop-blur-md p-3 sm:p-4 rounded-2xl text-center">
                            <div class="text-xs text-zinc-400">Rute Teroptimasi Hari Ini</div>
                            <div class="text-2xl font-bold text-white">487 KM</div>
                            <div class="text-emerald-400 text-sm">↓ 23% dari kemarin</div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Badge -->
                <div class="absolute -top-4 -right-4 sm:-top-6 sm:-right-6 bg-orange-500 text-white text-sm font-medium px-5 sm:px-6 py-2.5 sm:py-3 rounded-3xl shadow-xl rotate-6">
                    Powered by GIS
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="relative py-8 text-zinc-800 dark:text-zinc-500 text-sm flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 border-t border-white/10 mt-auto">
        <div>© 2026 PT. Angkutan Indonesia</div>
        <div class="flex gap-6">
            <a href="#" class="hover:text-white transition-colors">Dokumentasi</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </div>
</body>
</html>