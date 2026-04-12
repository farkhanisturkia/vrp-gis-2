<form action="{{ $order ? route('orders.update', $order->id) : route('orders.store') }}" 
      method="POST">
    
    @csrf
    @if ($order)
        @method('PUT')
    @endif

    <!-- Tanggal -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-zinc-300 mb-2">Tanggal Pengiriman</label>
        <input type="date" name="date"
            value="{{ old('date', $order?->date?->format('Y-m-d')) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base"
            required>
        @error('date') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <!-- From -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-zinc-300 mb-2">Dari</label>
        <select name="from_id" class="js-choices block w-full bg-zinc-800 border border-zinc-700 rounded-2xl py-3.5 px-5">
            <option value="">-- Pilih --</option>
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}" {{ old('from_id', $order?->from_id) == $c->id ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('from_id') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <!-- To -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-zinc-300 mb-2">Ke</label>
        <select name="to_id" class="js-choices block w-full bg-zinc-800 border border-zinc-700 rounded-2xl py-3.5 px-5">
            <option value="">-- Pilih --</option>
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}" {{ old('to_id', $order?->to_id) == $c->id ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('to_id') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <!-- Mandatory -->
    <div class="mb-8">
        <label class="block text-sm font-medium text-zinc-300 mb-2">Mandatory Areas</label>
        <select name="mandatories[]" multiple
            class="js-choices-multiple block w-full bg-zinc-800 border border-zinc-700 rounded-2xl py-3.5 px-5">
            @foreach ($coordinates as $c)
                <option value="{{ $c->id }}"
                    {{ in_array($c->id, old('mandatories', $order?->mandatories?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $c->area }}
                </option>
            @endforeach
        </select>
        @error('mandatories.*') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <!-- User -->
    @if (auth()->user()->role === 'admin')
    <div class="mb-6">
        <label class="block text-sm font-medium text-zinc-300 mb-2">User</label>
        <select name="user_id" class="js-choices block w-full bg-zinc-800 border border-zinc-700 rounded-2xl py-3.5 px-5">
            <option value="">-- Pilih --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id', $order?->user_id) == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    <!-- Armada -->
    <div class="mb-8">
        <label class="block text-sm font-medium text-zinc-300 mb-2">Armada</label>
        <select name="armada_id" class="js-choices block w-full bg-zinc-800 border border-zinc-700 rounded-2xl py-3.5 px-5">
            <option value="">-- Pilih --</option>
            @foreach ($armadas as $a)
                <option value="{{ $a->id }}" {{ old('armada_id', $order?->armada_id) == $a->id ? 'selected' : '' }}>
                    {{ $a->no_plat }} - {{ $a->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-zinc-800">
        <a href="{{ route('orders.index') }}"
           class="px-6 py-3.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-2xl text-center order-2 sm:order-1">
            Batal
        </a>

        <button type="submit"
            class="px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow-lg shadow-orange-500/30 order-1 sm:order-2">
            {{ $order ? 'Update Order' : 'Buat Order' }}
        </button>
    </div>
</form>