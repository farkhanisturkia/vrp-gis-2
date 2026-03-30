{{-- resources/views/orders/form.blade.php --}}

<form action="{{ $order ? route('orders.update', $order->id) : route('orders.store') }}" 
      method="POST">
    
    @csrf
    @if ($order)
        @method('PUT')
    @endif

    <!-- Tanggal -->
    <div class="mb-6">
        <label for="date" class="block text-sm font-medium text-zinc-300 mb-2">
            Tanggal Pengiriman
        </label>
        <input 
            type="date" 
            name="date" 
            id="date" 
            value="{{ old('date', $order?->date?->format('Y-m-d')) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white"
            required>
        @error('date')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- From -->
    <div class="mb-6">
        <label for="from_id" class="block text-sm font-medium text-zinc-300 mb-2">
            Dari (Asal)
        </label>
        <select name="from_id" id="from_id" 
                class="js-choices block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white">
            <option value="">-- Pilih Lokasi Asal --</option>
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}" {{ old('from_id', $order?->from_id) == $c->id ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('from_id')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- To -->
    <div class="mb-6">
        <label for="to_id" class="block text-sm font-medium text-zinc-300 mb-2">
            Ke (Tujuan)
        </label>
        <select name="to_id" id="to_id" 
                class="js-choices block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white">
            <option value="">-- Pilih Lokasi Tujuan --</option>
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}" {{ old('to_id', $order?->to_id) == $c->id ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('to_id')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Mandatory Areas -->
    <div class="mb-8">
        <label class="block text-sm font-medium text-zinc-300 mb-2">
            Mandatory Areas (Lewati Wajib)
        </label>
        <select name="mandatories[]" multiple 
                class="js-choices-multiple block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white h-40"
                data-placeholder="-- Select --">
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}" 
                    {{ in_array($c->id, old('mandatories', $order?->mandatories?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('mandatories.*')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- User (hanya admin) -->
    @if (auth()->check() && auth()->user()->role === 'admin')
    <div class="mb-6">
        <label for="user_id" class="block text-sm font-medium text-zinc-300 mb-2">
            User / Pengirim
        </label>
        <select name="user_id" id="user_id" 
                class="js-choices block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white">
            <option value="">-- Pilih User --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id', $order?->user_id) == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>
    @endif

    <!-- Armada -->
    <div class="mb-8">
        <label for="armada_id" class="block text-sm font-medium text-zinc-300 mb-2">
            Armada
        </label>
        <select name="armada_id" id="armada_id" 
                class="js-choices block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-4 px-5 text-white">
            <option value="">-- Pilih Armada --</option>
            @foreach ($armadas as $a)
                <option value="{{ $a->id }}" {{ old('armada_id', $order?->armada_id) == $a->id ? 'selected' : '' }}>
                    {{ $a->no_plat }} - {{ $a->name }}
                </option>
            @endforeach
        </select>
        @error('armada_id')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-4 pt-6 border-t border-zinc-800">
        <a href="{{ route('orders.index') }}" 
           class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium rounded-2xl transition-all">
            Batal
        </a>
        
        <button type="submit"
                class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all shadow-lg shadow-orange-500/30">
            {{ $order ? 'Update Order' : 'Buat Order' }}
        </button>
    </div>
</form>

<!-- Choices.js CDN & Inisialisasi -->
<!-- @push('styles')
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
@endpush -->