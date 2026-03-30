<x-app-layout>
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-white">Manajemen Orders</h1>
                <p class="text-zinc-400 text-sm">Kelola pesanan pengiriman dan rute</p>
            </div>
            
            @if (auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('orders.index') }}?modal=create"
               class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-xl flex items-center gap-2 text-sm">
                + Order
            </a>
            @endif
        </div>

        <!-- Table -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
            
            <!-- ✅ FIX SCROLL -->
            <div class="overflow-x-auto">
                <div class="min-w-[1100px]">

                    <table class="min-w-full whitespace-nowrap divide-y divide-zinc-800">
                        
                        <!-- HEADER -->
                        <thead class="bg-zinc-950 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-xs text-zinc-400">Tanggal</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Dari</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Ke</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Mandatory</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Status</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">User</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Armada</th>
                                <th class="px-6 py-3 text-xs text-zinc-400 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody class="divide-y divide-zinc-800">
                            @forelse ($orders as $order)
                            <tr class="hover:bg-zinc-800/40 transition">
                                
                                <td class="px-6 py-3 text-sm text-white">
                                    {{ $order->date?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="px-6 py-3 text-sm text-zinc-300 max-w-[140px] truncate">
                                    {{ $order->from?->area ?? '-' }}
                                </td>

                                <td class="px-6 py-3 text-sm text-zinc-300 max-w-[140px] truncate">
                                    {{ $order->to?->area ?? '-' }}
                                </td>

                                <td class="px-6 py-3">
                                    @if($order->mandatories->count())
                                        <button 
                                            onclick="openMandatoryModal({{ $order->id }})"
                                            class="px-3 py-1 text-xs bg-orange-500/10 text-orange-400 border border-orange-500/20 rounded-lg whitespace-nowrap">
                                            {{ $order->mandatories->count() }} Areas
                                        </button>
                                    @else
                                        <span class="text-zinc-500 text-sm">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    @php
                                        $status = $order->status;

                                        $classes = match($status) {
                                            'set'   => 'bg-zinc-700/40 text-zinc-300 border-zinc-600',
                                            'start' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                            'stop'  => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                            'end'   => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            default => 'bg-zinc-700 text-zinc-300'
                                        };
                                    @endphp

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl text-xs font-semibold border {{ $classes }}">
                                        
                                        <!-- DOT -->
                                        <span class="w-2 h-2 rounded-full 
                                            {{ $status === 'set' ? 'bg-zinc-400' : '' }}
                                            {{ $status === 'start' ? 'bg-blue-400' : '' }}
                                            {{ $status === 'stop' ? 'bg-yellow-400' : '' }}
                                            {{ $status === 'end' ? 'bg-emerald-400' : '' }}">
                                        </span>

                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-sm text-zinc-300 max-w-[120px] truncate">
                                    {{ $order->user?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-3 text-sm text-zinc-300 max-w-[160px] truncate">
                                    {{ $order->armada?->no_plat }} 
                                    <span class="text-zinc-500">({{ $order->armada?->name }})</span>
                                </td>

                                <td class="px-6 py-3 text-sm text-right whitespace-nowrap space-x-3">
                                    <a href="{{ route('orders.view', $order->id) }}" 
                                       class="text-emerald-400 hover:text-emerald-300">View</a>

                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                    <a href="{{ route('orders.index') }}?modal=edit&order_id={{ $order->id }}"
                                       class="text-orange-400 hover:text-orange-300">Edit</a>

                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin?')" 
                                                class="text-red-400 hover:text-red-500">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-zinc-500">
                                    Belum ada data
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

            <!-- PAGINATION -->
            <div class="px-6 py-3 border-t border-zinc-800">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- ✅ MODAL MANDATORY -->
    <div id="mandatoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-md">
            
            <div class="flex justify-between mb-4">
                <h3 class="text-white font-semibold">Mandatory Areas</h3>
                <button onclick="closeMandatoryModal()" class="text-zinc-400 text-xl">×</button>
            </div>

            <div id="mandatoryList" class="flex flex-wrap gap-2"></div>
        </div>
    </div>

    <!-- DATA -->
    <script>
        const mandatoryData = {
            @foreach ($orders as $order)
                {{ $order->id }}: [
                    @foreach ($order->mandatories as $m)
                        "{{ $m->area }}",
                    @endforeach
                ],
            @endforeach
        };
    </script>

    <!-- JS -->
    <script>
        function openMandatoryModal(id) {
            const modal = document.getElementById('mandatoryModal');
            const list = document.getElementById('mandatoryList');

            list.innerHTML = '';

            (mandatoryData[id] || []).forEach(area => {
                const el = document.createElement('span');
                el.className = "px-3 py-1 bg-orange-500/10 text-orange-400 border border-orange-500/20 rounded-full text-sm";
                el.innerText = area;
                list.appendChild(el);
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeMandatoryModal() {
            const modal = document.getElementById('mandatoryModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // klik luar modal
        window.onclick = function(e) {
            const modal = document.getElementById('mandatoryModal');
            if (e.target === modal) closeMandatoryModal();
        }
    </script>

    <!-- MODAL CREATE / EDIT -->
    @if (request('modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="bg-zinc-900 rounded-2xl w-full max-w-2xl">
            
            <div class="p-5 border-b border-zinc-800 flex justify-between">
                <h3 class="text-white text-sm">
                    {{ request('modal') === 'create' ? 'Buat Order' : 'Edit Order' }}
                </h3>
                <a href="{{ route('orders.index') }}">×</a>
            </div>

            <div class="p-6">
                @if (request('modal') === 'create')
                    @include('orders.form', ['order' => null])
                @elseif (request('modal') === 'edit' && $editOrder)
                    @include('orders.form', ['order' => $editOrder])
                @endif
            </div>
        </div>
    </div>
    @endif

</x-app-layout>