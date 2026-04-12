<x-app-layout>
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-white">Manajemen Orders</h1>
                <p class="text-zinc-400 text-sm">Kelola pesanan pengiriman dan rute</p>
            </div>
            
            @if (auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('orders.index') }}?modal=create"
               class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-2xl text-sm font-medium inline-flex items-center gap-2 justify-center">
                + Tambah Order
            </a>
            @endif
        </div>

        <!-- Table -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] divide-y divide-zinc-800">
                    
                    <!-- HEADER -->
                    <thead class="bg-zinc-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Dari</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Ke</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Mandatory</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400">Armada</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-zinc-400">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="divide-y divide-zinc-800 bg-zinc-900">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-zinc-800/60 transition-colors">
                            
                            <td class="px-6 py-4 text-white">
                                {{ $order->date?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-zinc-300 max-w-[140px] truncate">
                                {{ $order->from?->area ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-zinc-300 max-w-[140px] truncate">
                                {{ $order->to?->area ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($order->mandatories->count())
                                    <button 
                                        onclick="openMandatoryModal({{ $order->id }})"
                                        class="px-3 py-1 text-xs bg-orange-500/10 text-orange-400 border border-orange-500/20 rounded-lg">
                                        {{ $order->mandatories->count() }} Areas
                                    </button>
                                @else
                                    <span class="text-zinc-500 text-sm">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm">
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
                                    <span class="w-2 h-2 rounded-full
                                        {{ $status === 'set' ? 'bg-zinc-400' : '' }}
                                        {{ $status === 'start' ? 'bg-blue-400' : '' }}
                                        {{ $status === 'stop' ? 'bg-yellow-400' : '' }}
                                        {{ $status === 'end' ? 'bg-emerald-400' : '' }}">
                                    </span>
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-zinc-300 truncate">
                                {{ $order->user?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-zinc-300 truncate">
                                {{ $order->armada?->no_plat }}
                                <span class="text-zinc-500">({{ $order->armada?->name }})</span>
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-4">
                                <a href="{{ route('orders.view', $order->id) }}" 
                                   class="text-emerald-400 hover:text-emerald-500 font-medium">View</a>

                                @if (auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('orders.index') }}?modal=edit&order_id={{ $order->id }}"
                                   class="text-orange-400 hover:text-orange-500 font-medium">Edit</a>

                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus order ini?')" 
                                            class="text-red-400 hover:text-red-500 font-medium">
                                        Hapus
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

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-zinc-800">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Mandatory -->
    <div id="mandatoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-zinc-900 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Mandatory Areas</h3>
                <button onclick="closeMandatoryModal()" class="text-3xl text-zinc-400 hover:text-white">×</button>
            </div>

            <div id="mandatoryList" class="p-6 flex flex-wrap gap-2"></div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    @if (request('modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-zinc-900 rounded-3xl shadow-2xl w-full max-w-2xl mx-auto overflow-hidden">
            
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xl font-semibold">
                    {{ request('modal') === 'create' ? 'Buat Order' : 'Edit Order' }}
                </h3>
                <a href="{{ route('orders.index') }}" 
                   class="text-3xl leading-none text-zinc-400 hover:text-white">×</a>
            </div>

            <div class="p-6 sm:p-8">
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