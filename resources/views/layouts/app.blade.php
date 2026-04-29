<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
        <title>{{ config('app.name', 'Sistem Informasi Rute Angkutan Pasir (SIRUSIR)') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        @vite(['resources/css/app.css'])
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-zinc-300 dark:bg-zinc-950 text-zinc-100">

        <div class="flex h-screen overflow-hidden">

            <!-- Sidebar (Mobile Responsive) -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Top Header (Mobile) -->
                <header class="bg-zinc-300 dark:bg-zinc-900 border-b border-zinc-400 dark:border-zinc-800 px-4 sm:px-6 py-4 flex items-center justify-between lg:hidden">
                    <div class="flex items-center gap-3">
                        <button id="mobile-menu-button" class="p-2 text-zinc-800 dark:text-zinc-400 hover:text-orange-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">V</span>
                            </div>
                            <span class="font-semibold tracking-tight text-zinc-800 dark:text-zinc-300">SIRUSIR</span>
                        </div>
                    </div>

                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <div class="relative lg:hidden">
                            <button onclick="openNotificationModal()" class="p-2 text-zinc-800 dark:text-zinc-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="notif-count-mobile" class="hidden absolute top-1 right-1 items-center justify-center h-4 w-4 text-[9px] font-bold text-white bg-red-600 rounded-full ring-2 ring-zinc-300 dark:ring-zinc-900">
                                    0
                                </span>
                            </button>
                        </div>
                    @endif
                </header>

                <!-- Desktop Page Header -->
                @isset($header)
                    <header class="hidden lg:block bg-zinc-900 border-b border-zinc-800 px-6 py-5">
                        {{ $header }}
                    </header>
                @endisset

                <!-- Main Content -->
                <main class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8 bg-zinc-300 dark:bg-zinc-950">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <div id="notifModal" class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 border-b dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-black dark:text-white">Notifikasi Order</h3>
                    <button onclick="closeNotifModal()" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400">&times;</button>
                </div>
                <div id="notif-list" class="max-h-[400px] overflow-y-auto p-2">
                    <p class="text-center py-4 text-zinc-500">Tidak ada pesan baru.</p>
                </div>
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

            async function fetchNotifications() {
                const res = await fetch("{{ route('notifications.get') }}");
                const data = await res.json();

                const badgeDesktop = document.getElementById('notif-count');
                const badgeMobile = document.getElementById('notif-count-mobile');
                const list = document.getElementById('notif-list');

                const unreadCount = data.filter(msg => msg.is_read == 0).length;

                [badgeDesktop, badgeMobile].forEach(badge => {
                    if (badge) {
                        if (unreadCount > 0) {
                            badge.innerText = unreadCount;
                            badge.classList.remove('hidden');
                            badge.classList.add('flex');
                        } else {
                            badge.classList.add('hidden');
                            badge.classList.remove('flex');
                        }
                    }
                });

                if (data.length > 0) {
                    list.innerHTML = data.map(msg => {
                        const isUnread = msg.is_read == 0;
                        
                        const bgColor = isUnread 
                            ? 'bg-orange-500/10 border-orange-500/50' 
                            : 'bg-zinc-100 dark:bg-zinc-800/50 border-transparent opacity-60';
                        
                        const dotIndicator = isUnread
                            ? '<span class="w-2 h-2 bg-orange-500 rounded-full"></span>'
                            : '';

                        return `
                            <div onclick="handleNotificationClick(${msg.id}, ${msg.order_id}, ${msg.is_read})" 
                                class="p-4 mb-2 rounded-xl cursor-pointer border transition-all hover:scale-[1.01] ${bgColor}">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2">
                                        ${dotIndicator}
                                        <span class="text-xs font-bold uppercase ${isUnread ? 'text-orange-500' : 'text-zinc-800 dark:text-zinc-500'}">${msg.status}</span>
                                    </div>
                                    <span class="text-[10px] text-zinc-700 dark:text-zinc-500">${new Date(msg.created_at).toLocaleString('id-ID')}</span>
                                </div>
                                <p class="text-sm text-zinc-950 dark:text-zinc-200 mt-1">${msg.content}</p>
                                <p class="text-[11px] text-zinc-700 dark:text-zinc-400 mt-2 font-medium">Oleh: ${msg.user.name} | Order #${msg.order_id}</p>
                            </div>
                        `;
                    }).join('');
                } else {
                    list.innerHTML = '<p class="text-center py-4 text-zinc-500 text-sm">Tidak ada riwayat pesan.</p>';
                }
            }      

            async function handleNotificationClick(messageId, orderId, isRead) {
                if (isRead == 0) {
                    try {
                        await fetch(`/notifications/${messageId}/read`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                    } catch (err) {
                        console.error("Gagal update status baca:", err);
                    }
                }

                window.location.href = `/orders/${orderId}`;
            }

            function openNotificationModal() {
                document.getElementById('notifModal').classList.remove('hidden');
                document.getElementById('notifModal').classList.add('flex');
            }

            function closeNotifModal() {
                document.getElementById('notifModal').classList.add('hidden');
                document.getElementById('notifModal').classList.remove('flex');
            }

            document.addEventListener('DOMContentLoaded', function() {
                @if(auth()->user() && auth()->user()->isAdmin())
                    fetchNotifications();
                @endif
            });
        </script>
    </body>
</html>