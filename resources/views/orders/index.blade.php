<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tombol Tambah -->
            <div class="mb-6 flex justify-end">
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('orders.index') }}?modal=create"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition duration-150 ease-in-out flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create
                    </a>
                @endif
            </div>

            <!-- Notifikasi -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabel Orders -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mandatory Areas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->date ? $order->date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->from?->area ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->to?->area ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($order->mandatories as $m)
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $m->area }}
                                                </span>
                                            @empty
                                                <span class="text-gray-500 text-sm">—</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->user?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->armada?->no_plat ?? '-' }} ({{ $order->armada?->name ?? '-' }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('orders.view', $order->id) }}"
                                            class="text-green-600 hover:text-green-800">View</a>
                                        @if (auth()->check() && auth()->user()->role === 'admin')
                                            <a href="{{ route('orders.index') }}?modal=edit&order_id={{ $order->id }}"
                                                class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')"
                                                        class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        No orders
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-6 pb-6">
                    {{ $orders->links() }}
                </div>
            </div>

            <!-- Modal Create / Edit -->
            @if (request()->has('modal'))
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto">
                    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 my-8">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ request('modal') === 'create' ? 'Add New Order Form' : 'Edit Order Form' }}
                            </h3>
                        </div>

                        <div class="p-6">
                            @if (request('modal') === 'create')
                                <form action="{{ route('orders.store') }}" method="POST">
                                    @csrf

                                    <!-- Tanggal -->
                                    <div class="mb-5">
                                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <input type="date" name="date" id="date" value="{{ old('date') }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               required>
                                        @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- From -->
                                    <div class="mb-5">
                                        <label for="from_id" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                                        <select name="from_id" id="from_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                data-placeholder="-- Select --">
                                            <option value=""></option>
                                            @foreach ($coordinates as $c)
                                                <option value="{{ $c->id }}" {{ old('from_id') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('from_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- To -->
                                    <div class="mb-5">
                                        <label for="to_id" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                                        <select name="to_id" id="to_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                data-placeholder="-- Select --">
                                            <option value=""></option>
                                            @foreach ($coordinates as $c)
                                                <option value="{{ $c->id }}" {{ old('to_id') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('to_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Mandatory Areas -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mandatories</label>
                                        <select name="mandatories[]" multiple class="js-choices js-choices-multiple block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-32"
                                                data-placeholder="-- Select --">
                                            @foreach ($coordinates as $c)
                                                <option value="{{ $c->id }}" {{ in_array($c->id, old('mandatories', [])) ? 'selected' : '' }}>
                                                    {{ $c->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mandatories.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- User -->
                                    <div class="mb-5">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                                        <select name="user_id" id="user_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                data-placeholder="-- Select --">
                                            <option value=""></option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Armada -->
                                    <div class="mb-6">
                                        <label for="armada_id" class="block text-sm font-medium text-gray-700 mb-1">Armada</label>
                                        <select name="armada_id" id="armada_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                data-placeholder="-- Select --">
                                            <option value=""></option>
                                            @foreach ($armadas as $a)
                                                <option value="{{ $a->id }}" {{ old('armada_id') == $a->id ? 'selected' : '' }}>
                                                    {{ $a->no_plat }} ({{ $a->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('armada_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mt-8 flex justify-end gap-3">
                                        <a href="{{ route('orders.index') }}"
                                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            Create
                                        </button>
                                    </div>
                                </form>

                            @elseif (request('modal') === 'edit' && request('order_id'))
                                @php
                                    $editOrder = \App\Models\Order::with('mandatories')->find(request('order_id'));
                                @endphp

                                @if($editOrder)
                                    <form action="{{ route('orders.update', $editOrder->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Tanggal -->
                                        <div class="mb-5">
                                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                            <input type="date" name="date" id="date"
                                                   value="{{ old('date', $editOrder->date?->format('Y-m-d')) }}"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   required>
                                            @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- From -->
                                        <div class="mb-5">
                                            <label for="from_id" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                                            <select name="from_id" id="from_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    data-placeholder="-- Select --">
                                                <option value=""></option>
                                                @foreach ($coordinates as $c)
                                                    <option value="{{ $c->id }}"
                                                            {{ old('from_id', $editOrder->from_id) == $c->id ? 'selected' : '' }}>
                                                        {{ $c->area }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('from_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- To -->
                                        <div class="mb-5">
                                            <label for="to_id" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                                            <select name="to_id" id="to_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    data-placeholder="-- Select --">
                                                <option value=""></option>
                                                @foreach ($coordinates as $c)
                                                    <option value="{{ $c->id }}"
                                                            {{ old('to_id', $editOrder->to_id) == $c->id ? 'selected' : '' }}>
                                                        {{ $c->area }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('to_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Mandatory Areas -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mandatories</label>
                                            <select name="mandatories[]" multiple class="js-choices js-choices-multiple block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-32"
                                                    data-placeholder="-- Select --">
                                                @foreach ($coordinates as $c)
                                                    <option value="{{ $c->id }}"
                                                            {{ in_array($c->id, old('mandatories', $editOrder->mandatories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                        {{ $c->area }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mandatories.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- User -->
                                        <div class="mb-5">
                                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                                            <select name="user_id" id="user_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    data-placeholder="-- Select --">
                                                <option value=""></option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}"
                                                            {{ old('user_id', $editOrder->user_id) == $u->id ? 'selected' : '' }}>
                                                        {{ $u->name }} ({{ $u->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Armada -->
                                        <div class="mb-6">
                                            <label for="armada_id" class="block text-sm font-medium text-gray-700 mb-1">Armada</label>
                                            <select name="armada_id" id="armada_id" class="js-choices block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    data-placeholder="-- Select --">
                                                <option value=""></option>
                                                @foreach ($armadas as $a)
                                                    <option value="{{ $a->id }}"
                                                            {{ old('armada_id', $editOrder->armada_id) == $a->id ? 'selected' : '' }}>
                                                        {{ $a->name ?? $a->no_plat ?? 'Armada #' . $a->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('armada_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="mt-8 flex justify-end gap-3">
                                            <a href="{{ route('orders.index') }}"
                                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                                Cancel
                                            </a>
                                            <button type="submit"
                                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-red-600 font-medium">Order not found</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Choices.js CDN & Inisialisasi -->
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Single select
                document.querySelectorAll('.js-choices:not([multiple])').forEach(el => {
                    new Choices(el, {
                        searchEnabled: true,
                        itemSelectText: '',
                        noResultsText: 'Tidak ditemukan',
                        noChoicesText: 'Tidak ada pilihan',
                        shouldSort: false,
                        placeholderValue: el.dataset.placeholder || 'Pilih...',
                    });
                });

                // Multiple select (mandatory)
                document.querySelectorAll('.js-choices-multiple').forEach(el => {
                    new Choices(el, {
                        removeItemButton: true,
                        duplicateItemsAllowed: false,
                        searchEnabled: true,
                        editItems: true,
                        noResultsText: 'Tidak ditemukan',
                        noChoicesText: 'Tidak ada pilihan',
                        placeholderValue: el.dataset.placeholder || 'Pilih area...',
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>