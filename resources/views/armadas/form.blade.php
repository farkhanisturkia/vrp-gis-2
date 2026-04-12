<form action="{{ $armada ? route('armadas.update', $armada->id) : route('armadas.store') }}" 
      method="POST">
    
    @csrf
    @if($armada)
        @method('PUT')
    @endif

    <!-- Nama Armada -->
    <div class="mb-6">
        <label for="name" class="block text-sm font-medium text-zinc-300 mb-2">
            Nama Armada
        </label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            value="{{ old('name', $armada?->name) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
        @error('name')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kapasitas -->
    <div class="mb-6">
        <label for="capacity" class="block text-sm font-medium text-zinc-300 mb-2">
            Kapasitas (KG)
        </label>
        <input 
            type="number" 
            name="capacity" 
            id="capacity" 
            value="{{ old('capacity', $armada?->capacity) }}"
            min="1"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
        @error('capacity')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- No Plat -->
    <div class="mb-8">
        <label for="no_plat" class="block text-sm font-medium text-zinc-300 mb-2">
            Nomor Plat
        </label>
        <input 
            type="text" 
            name="no_plat" 
            id="no_plat" 
            value="{{ old('no_plat', $armada?->no_plat) }}"
            class="block w-full bg-zinc-800 border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500 font-mono uppercase"
            required>
        @error('no_plat')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-zinc-800">
        <a href="{{ route('armadas.index') }}"
           class="px-6 py-3.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium rounded-2xl transition-all text-center order-2 sm:order-1">
            Batal
        </a>
       
        <button type="submit"
                class="px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all shadow-lg shadow-orange-500/30 order-1 sm:order-2">
            {{ $armada ? 'Update Armada' : 'Simpan Armada Baru' }}
        </button>
    </div>
</form>