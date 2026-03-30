<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VRP-GIS Angkutan | Optimalisasi Rute Pengangkutan</title>
    
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
    @endif>
</head>

<body class="bg-zinc-950 text-white min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(at_top_right,#1E3A8A_0%,transparent_50%)] opacity-40"></div>
    
    <div class="relative w-full max-w-7xl mx-auto px-6 py-12">
        <!-- Header -->
        <header class="flex justify-between items-center mb-16">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-2xl">V</div>
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">VRP-GIS</h1>
                    <p class="text-xs text-zinc-400 -mt-1">Angkutan PT</p>
                </div>
            </div>
            
            @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/users') }}" 
                           class="px-6 py-3 bg-white text-zinc-900 hover:bg-orange-500 hover:text-white font-medium rounded-2xl transition-all duration-300 flex items-center gap-2">
                            <span>Masuk ke Dashboard</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-6 py-3 border border-zinc-700 hover:border-white/50 font-medium rounded-2xl transition-all">
                            Masuk
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-3xl text-sm">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="font-medium">Sistem Optimalisasi Rute Real-time</span>
                </div>

                <h1 class="text-6xl lg:text-7xl font-bold tracking-tighter leading-none">
                    Rute Terbaik.<br>
                    <span class="text-orange-500">Pengiriman Efisien.</span>
                </h1>

                <p class="text-xl text-zinc-400 max-w-lg">
                    Platform VRP-GIS terintegrasi untuk manajemen armada angkutan PT. 
                    Optimasi rute, visualisasi peta, dan analisis performa dalam satu sistem.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all flex items-center gap-3 text-lg">
                        Mulai Sekarang
                        <span aria-hidden="true">→</span>
                    </a>
                    
                    <a href="#" 
                       class="px-8 py-4 border border-white/30 hover:border-white/60 font-medium rounded-2xl transition-all flex items-center gap-3">
                        Lihat Demo
                    </a>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-6 pt-8">
                    <div class="flex gap-4">
                        <div class="text-orange-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314-11.314z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold">VRP Intelligence</h4>
                            <p class="text-sm text-zinc-400">Algoritma Vehicle Routing Problem canggih</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-orange-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2 2 2 0 01-2-2 2 2 0 01-2-2 2 2 0 012-2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold">GIS Visualization</h4>
                            <p class="text-sm text-zinc-400">Peta interaktif + heatmap rute</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="relative">
                <div class="bg-zinc-900/70 backdrop-blur-xl border border-white/10 rounded-3xl p-3 shadow-2xl">
                    <div class="aspect-video bg-zinc-950 rounded-2xl overflow-hidden relative">
                        <!-- Mock Peta -->
                        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/800/450')] bg-cover"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/60 to-transparent"></div>
                        
                        <!-- Rute Lines (simulasi) -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 800 450">
                            <path d="M150 300 Q400 150 650 280" fill="none" stroke="#f97316" stroke-width="4" stroke-opacity="0.7" stroke-dasharray="8 4"/>
                            <circle cx="150" cy="300" r="12" fill="#f97316"/>
                            <circle cx="650" cy="280" r="12" fill="#f97316"/>
                        </svg>

                        <div class="absolute bottom-6 left-6 bg-black/70 backdrop-blur-md px-5 py-3 rounded-2xl text-sm">
                            <div class="flex items-center gap-3">
                                <div class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-xl text-xs font-medium">12 Kendaraan</div>
                                <div class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-xl text-xs font-medium">98% Efisiensi</div>
                            </div>
                        </div>

                        <div class="absolute top-6 right-6 bg-black/70 backdrop-blur-md p-4 rounded-2xl">
                            <div class="text-xs text-zinc-400">Rute Teroptimasi Hari Ini</div>
                            <div class="text-2xl font-bold text-white">487 KM</div>
                            <div class="text-emerald-400 text-sm">↓ 23% dari kemarin</div>
                        </div>
                    </div>
                </div>

                <!-- Floating Badge -->
                <div class="absolute -top-6 -right-6 bg-orange-500 text-white text-sm font-medium px-6 py-3 rounded-3xl shadow-xl rotate-6">
                    Powered by GIS
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-zinc-500 text-sm flex gap-8">
        <div>© 2026 PT. Angkutan Indonesia</div>
        <div class="flex gap-6">
            <a href="#" class="hover:text-white transition-colors">Dokumentasi</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </div>
</body>
</html>