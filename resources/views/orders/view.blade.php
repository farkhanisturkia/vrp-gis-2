<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Order #{{ $order->id }} - {{ $order->date?->format('d M Y') }}
            </h2>
            <a href="{{ route('orders.index') }}" 
               class="text-indigo-600 hover:text-indigo-800 font-medium">
                ← Kembali ke Daftar Order
            </a>
        </div>
    </x-slot>

    <div class="flex h-[calc(100vh-140px)]">  <!-- Sesuaikan dengan header + nav -->
        <!-- MAP SECTION -->
        <div id="map" class="flex-grow relative"></div>

        <!-- SIDE PANEL -->
        <div class="w-80 bg-white border-l shadow-sm flex flex-col">
            <div class="p-4 border-b">
                <h2 class="font-bold text-lg">Urutan Rute</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $order->from?->area ?? 'Titik Awal' }} → {{ $order->to?->area ?? 'Titik Akhir' }}
                </p>
            </div>

            <div class="p-4 flex-1 overflow-auto">
                <!-- GPS Status -->
                <div class="mb-4">
                    <strong class="text-sm">Start (GPS Anda):</strong>
                    <div id="gps-status" class="text-gray-600 text-sm mt-1">Menunggu lokasi...</div>
                </div>

                <button id="btnLoad"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition mb-6">
                    Tampilkan Rute Lengkap
                </button>

                <!-- Daftar Step -->
                <ul id="stepList" class="space-y-2 text-sm"></ul>
            </div>

            <!-- Info Order -->
            <div class="p-4 border-t bg-gray-50 text-xs">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500">User</span><br>
                        <strong>{{ $order->user?->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-500">Armada</span><br>
                        <strong>{{ $order->armada?->no_plat ?? '-' }} ({{ $order->armada?->name ?? '-' }})</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .step-btn {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f3f4f6;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s;
        }
        .step-btn:hover {
            background: #e5e7eb;
        }
        .step-btn.active {
            background: #2563eb;
            color: white;
        }
        .marker-label {
            background: #2563eb;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Data dari Laravel
        const orderData = {
            from: {!! json_encode($order->from) !!},
            to: {!! json_encode($order->to) !!},
            mandatories: {!! json_encode($order->mandatories) !!}
        };

        let startPoint = null;
        let activeRoute = null;
        let map = null;
        let routeControl = null;

        // Inisialisasi Map
        map = L.map('map').setView([-7.25, 112.75], 10);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // GPS Tracking
        const gpsStatus = document.getElementById('gps-status');
        navigator.geolocation.watchPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            startPoint = { lat, lng, label: 'Posisi Saya Saat Ini' };
            gpsStatus.innerHTML = `${lat.toFixed(5)}, ${lng.toFixed(5)} <span class="text-green-600">(aktif)</span>`;
        }, () => {
            gpsStatus.innerHTML = 'GPS tidak tersedia atau ditolak';
        });

        // Buat array semua titik dalam urutan
        let routePoints = [];

        if (orderData.from) {
            routePoints.push({
                lat: parseFloat(orderData.from.lat),
                lng: parseFloat(orderData.from.long),
                label: orderData.from.area,
                type: 'from'
            });
        }

        // Tambahkan mandatories
        orderData.mandatories.forEach((m, index) => {
            routePoints.push({
                lat: parseFloat(m.lat),
                lng: parseFloat(m.long),
                label: m.area,
                type: 'mandatory',
                order: index + 1
            });
        });

        if (orderData.to) {
            routePoints.push({
                lat: parseFloat(orderData.to.lat),
                lng: parseFloat(orderData.to.long),
                label: orderData.to.area,
                type: 'to'
            });
        }

        // Tambahkan marker ke map
        routePoints.forEach((point, index) => {
            const iconHtml = point.type === 'from' 
                ? `<div class="marker-label" style="background:#10b981">A</div>`
                : point.type === 'to' 
                    ? `<div class="marker-label" style="background:#ef4444">B</div>`
                    : `<div class="marker-label">${point.order}</div>`;

            const icon = L.divIcon({
                className: '',
                html: iconHtml,
                iconSize: [28, 28]
            });

            L.marker([point.lat, point.lng], { icon })
                .addTo(map)
                .bindPopup(`<b>${point.label}</b>`);
        });

        // Fit map ke semua marker
        if (routePoints.length > 0) {
            const bounds = L.latLngBounds(routePoints.map(p => [p.lat, p.lng]));
            map.fitBounds(bounds, { padding: [50, 50] });
        }

        // Event Tombol Tampilkan Rute
        document.getElementById('btnLoad').addEventListener('click', () => {
            if (!startPoint) {
                alert('GPS belum aktif. Tunggu sebentar atau izinkan lokasi.');
                return;
            }

            if (routePoints.length === 0) {
                alert('Tidak ada titik rute pada order ini.');
                return;
            }

            renderStepList();
            showFullRoute();
        });

        function renderStepList() {
            const list = document.getElementById('stepList');
            list.innerHTML = '';

            const fullPoints = [startPoint, ...routePoints];

            fullPoints.slice(0, -1).forEach((current, i) => {
                const next = fullPoints[i + 1];
                if (!next) return;

                const btn = document.createElement('button');
                btn.className = 'step-btn';
                btn.innerHTML = `
                    <div class="font-medium">${current.label}</div>
                    <div class="text-xs text-gray-500">→ ${next.label}</div>
                `;
                btn.onclick = () => showSegment(current, next, btn);
                list.appendChild(btn);
            });
        }

        async function showSegment(from, to, btn) {
            // Hapus rute sebelumnya jika ada
            if (activeRoute) map.removeLayer(activeRoute);

            document.querySelectorAll('.step-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Menggunakan Leaflet Routing Machine (sesuai layout Anda)
            if (routeControl) map.removeControl(routeControl);

            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(from.lat, from.lng),
                    L.latLng(to.lat, to.lng)
                ],
                routeWhileDragging: true,
                showAlternatives: false,
                lineOptions: {
                    styles: [{ color: '#2563eb', weight: 6 }]
                },
                createMarker: () => null  // Marker sudah dibuat manual
            }).addTo(map);
        }

        async function showFullRoute() {
            if (routeControl) map.removeControl(routeControl);

            const waypoints = [startPoint, ...routePoints].map(p => 
                L.latLng(p.lat, p.lng)
            );

            routeControl = L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: false,
                showAlternatives: false,
                lineOptions: {
                    styles: [{ color: '#2563eb', weight: 7, opacity: 0.9 }]
                }
            }).addTo(map);
        }
    });
    </script>
    @endpush
</x-app-layout>