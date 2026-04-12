<form action="{{ $coordinate ? route('coordinates.update', $coordinate->id) : route('coordinates.store') }}" 
      method="POST">
    
    @csrf
    @if($coordinate)
        @method('PUT')
    @endif

    <!-- Area -->
    <div class="mb-6">
        <label for="area" class="block text-sm font-medium text-zinc-300 mb-2">
            Nama Area / Lokasi
        </label>
        <input 
            type="text" 
            name="area" 
            id="area" 
            value="{{ old('area', $coordinate?->area) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
        @error('area')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Latitude -->
    <div class="mb-6">
        <label for="lat" class="block text-sm font-medium text-zinc-300 mb-2">
            Latitude
        </label>
        <input 
            type="number" 
            step="0.00000001"
            name="lat" 
            id="lat" 
            value="{{ old('lat', $coordinate?->lat) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500 font-mono"
            required>
        @error('lat')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Longitude -->
    <div class="mb-8">
        <label for="long" class="block text-sm font-medium text-zinc-300 mb-2">
            Longitude
        </label>
        <input 
            type="number" 
            step="0.00000001"
            name="long" 
            id="long" 
            value="{{ old('long', $coordinate?->long) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500 font-mono"
            required>
        @error('long')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-zinc-800">
        <a href="{{ route('coordinates.index') }}"
           class="px-6 py-3.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium rounded-2xl transition-all text-center order-2 sm:order-1">
            Batal
        </a>
       
        <button type="submit"
                class="px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all shadow-lg shadow-orange-500/30 order-1 sm:order-2">
            {{ $coordinate ? 'Update Coordinate' : 'Simpan Coordinate Baru' }}
        </button>
    </div>
</form>