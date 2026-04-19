<x-app-layout>
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                    Detail Order #{{ $order->id }}
                </h1>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    {{ $order->date?->format('d M Y') }}
                </p>
            </div>

            <a href="{{ route('orders.index') }}" 
               class="text-orange-700 dark:text-orange-400 hover:text-orange-500 font-medium">
                ← Kembali
            </a>
        </div>

        <!-- Layout -->
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- MAP -->
            <div class="w-full lg:flex-1 h-[300px] sm:h-[400px] lg:h-auto bg-zinc-300 dark:bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden relative">
                <div id="map" class="w-full h-full"></div>
            </div>

            <!-- SIDE PANEL -->
            <div class="w-full lg:w-80 bg-zinc-300 dark:bg-zinc-900 border border-zinc-800 rounded-3xl flex flex-col overflow-hidden">

                <!-- HEADER -->
                <div class="p-6 border-b bg-zinc-400 dark:bg-zinc-800 space-y-3">
                    <div>
                        <h2 class="text-black dark:text-white font-semibold">
                            Rute Pengiriman
                        </h2>
                        <p class="text-zinc-800 dark:text-zinc-400 text-sm mt-1">
                            {{ $order->from?->area ?? '-' }} → {{ $order->to?->area ?? '-' }}
                        </p>
                    </div>

                    <!-- STATUS -->
                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="text-xs text-zinc-800 dark:text-zinc-400 mb-1 block">
                                Status Order
                            </label>

                            <select name="status"
                                onchange="this.form.submit()"
                                class="w-full text-black dark:text-white dark:bg-zinc-800 border border-zinc-700 rounded-2xl px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500">
                                
                                <option value="set" {{ $order->status === 'set' ? 'selected' : '' }}>Set</option>
                                <option value="start" {{ $order->status === 'start' ? 'selected' : '' }}>Start</option>
                                <option value="stop" {{ $order->status === 'stop' ? 'selected' : '' }}>Stop</option>
                                <option value="end" {{ $order->status === 'end' ? 'selected' : '' }}>End</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- CONTENT -->
                <div class="p-6 flex-1 overflow-y-auto space-y-6 max-h-[400px] lg:max-h-none">

                    <!-- GPS -->
                    <div>
                        <div class="text-xs text-zinc-800 dark:text-zinc-400 mb-1">Start (GPS Anda)</div>
                        <div id="gps-status" class="text-sm text-zinc-800 dark:text-zinc-300">
                            Menunggu lokasi...
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <button id="btnLoad"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3.5 rounded-2xl transition">
                        Tampilkan Rute
                    </button>

                    <!-- STEP LIST -->
                    <div>
                        <div class="text-xs text-zinc-800 dark:text-zinc-400 mb-2">Detail Perjalanan</div>
                        <ul id="stepList" class="space-y-2"></ul>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="p-6 border-t border-zinc-800 bg-zinc-400 dark:bg-zinc-950 text-sm">
                    <div class="flex justify-between">
                        <div>
                            <div class="text-zinc-800 dark:text-zinc-400 text-xs">User</div>
                            <div class="text-black dark:text-white font-medium">
                                {{ $order->user?->name ?? '-' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-zinc-800 dark:text-zinc-400 text-xs">Armada</div>
                            <div class="text-black dark:text-white font-medium">
                                {{ $order->armada?->no_plat ?? '-' }}
                            </div>
                            <div class="text-zinc-900 dark:text-zinc-500 text-xs">
                                {{ $order->armada?->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .step-btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: #18181b;
            border: 1px solid #27272a;
            text-align: left;
            transition: all 0.2s;
        }

        .step-btn:hover {
            background: #27272a;
        }

        .step-btn.active {
            background: #f97316;
            color: white;
            border-color: #f97316;
        }

        .marker-label {
            background: #f97316;
            color: white;
            border-radius: 9999px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const orderData = {
                from: {!! json_encode($order->from) !!},
                to: {!! json_encode($order->to) !!},
                mandatories: {!! json_encode($order->mandatories) !!}
            };

            let startPoint = null;
            let routeControl = null;
            let markers = [];

            const map = L.map('map', {
                scrollWheelZoom: false
            }).setView([-7.25, 112.75], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            const gpsStatus = document.getElementById('gps-status');
            const btnLoad = document.getElementById('btnLoad');

            // ================= GPS =================
            navigator.geolocation.watchPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                startPoint = { lat, lng, label: 'Posisi Saya' };

                gpsStatus.innerHTML = `${lat.toFixed(5)}, ${lng.toFixed(5)} 
                    <span class="text-green-400">(aktif)</span>`;
            });

            // ================= PREPARE DATA =================
            const mandatoryPoints = orderData.mandatories.map(m => ({
                lat: +m.lat,
                lng: +m.long,
                label: m.area
            }));

            const endPoint = orderData.to ? {
                lat: +orderData.to.lat,
                lng: +orderData.to.long,
                label: orderData.to.area
            } : null;

            const fromPoint = orderData.from ? {
                lat: +orderData.from.lat,
                lng: +orderData.from.long,
                label: orderData.from.area
            } : null;

            // ================= INITIAL MARKERS =================
            function renderInitialMarkers() {
                const points = [];

                if (fromPoint) points.push({ ...fromPoint, label: 'FROM: ' + fromPoint.label });
                mandatoryPoints.forEach((p, i) => {
                    points.push({ ...p, label: `M${i+1}: ${p.label}` });
                });
                if (endPoint) points.push({ ...endPoint, label: 'TO: ' + endPoint.label });

                points.forEach((p, i) => {
                    const icon = L.divIcon({
                        html: `<div class="marker-label">${i+1}</div>`
                    });

                    const marker = L.marker([p.lat, p.lng], { icon })
                        .addTo(map)
                        .bindPopup(p.label);

                    markers.push(marker);
                });

                if (points.length) {
                    map.fitBounds(points.map(p => [p.lat, p.lng]), { padding: [50,50] });
                }
            }

            renderInitialMarkers();

            // ================= BUTTON CLICK =================
            btnLoad.onclick = async () => {
                if (!startPoint) return alert('GPS belum aktif');

                btnLoad.innerText = 'Loading...';
                btnLoad.disabled = true;

                try {
                    const response = await fetch('/api/vrp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: fromPoint,
                            end: endPoint,
                            points: mandatoryPoints
                        })
                    });

                    const vrpRoute = await response.json();

                    const optimizedRoute = [
                        startPoint,
                        ...vrpRoute
                    ];

                    // ================= CLEAR OLD =================
                    if (routeControl) map.removeControl(routeControl);
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];

                    // ================= RENDER MARKERS =================
                    optimizedRoute.forEach((p, i) => {
                        const icon = L.divIcon({
                            html: `<div class="marker-label">${i+1}</div>`
                        });

                        const marker = L.marker([p.lat, p.lng], { icon })
                            .addTo(map)
                            .bindPopup(p.label || `Point ${i+1}`);

                        markers.push(marker);
                    });

                    // ================= ROUTE =================
                    routeControl = L.Routing.control({
                        waypoints: optimizedRoute.map(p => L.latLng(p.lat, p.lng)),
                        createMarker: () => null
                    }).addTo(map);

                    // ================= STEP LIST =================
                    const list = document.getElementById('stepList');
                    list.innerHTML = '';

                    optimizedRoute.slice(0, -1).forEach((cur, i) => {
                        const next = optimizedRoute[i + 1];

                        const btn = document.createElement('button');
                        btn.className = 'step-btn';

                        btn.innerHTML = `
                            <div class="text-white text-sm font-medium">${cur.label || 'Point'}</div>
                            <div class="text-xs text-zinc-400">→ ${next.label || 'Point'}</div>
                        `;

                        btn.onclick = () => {
                            document.querySelectorAll('.step-btn').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');

                            if (routeControl) map.removeControl(routeControl);

                            routeControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(cur.lat, cur.lng),
                                    L.latLng(next.lat, next.lng)
                                ],
                                createMarker: () => null
                            }).addTo(map);
                        };

                        list.appendChild(btn);
                    });

                } catch (err) {
                    console.error(err);
                    alert('Gagal ambil rute');
                }

                btnLoad.innerText = 'Tampilkan Rute';
                btnLoad.disabled = false;
            };
        });
    </script>
    @endpush
</x-app-layout>