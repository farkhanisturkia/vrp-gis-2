{{-- resources/views/users/form.blade.php --}}

<form action="{{ $user ? route('users.update', $user->id) : route('users.store') }}"
      method="POST">

    @csrf
    @if($user)
        @method('PUT')
    @endif

    <!-- Name -->
    <div class="mb-6">
        <label for="name" class="block text-sm font-medium text-zinc-800 dark:text-zinc-300 mb-2">
            Nama Lengkap
        </label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $user?->name) }}"
            class="block w-full bg-zinc-200 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
        @error('name')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-6">
        <label for="email" class="block text-sm font-medium text-zinc-800 dark:text-zinc-300 mb-2">
            Alamat Email
        </label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $user?->email) }}"
            class="block w-full bg-zinc-200 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
        @error('email')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Role -->
    <div class="mb-6">
        <label for="role" class="block text-sm font-medium text-zinc-800 dark:text-zinc-300 mb-2">
            Role / Jabatan
        </label>
        <select
            name="role"
            id="role"
            class="block w-full bg-zinc-200 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base">
            <option value="user" {{ old('role', $user?->role) === 'user' ? 'selected' : '' }}>
                User / Operator
            </option>
            <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>
                Administrator
            </option>
        </select>
        @error('role')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-zinc-800 dark:text-zinc-300 mb-2">
            {{ $user ? 'Password Baru (kosongkan jika tidak ingin diubah)' : 'Password' }}
        </label>
        <input
            type="password"
            name="password"
            id="password"
            class="block w-full bg-zinc-200 dark:bg-zinc-800 text-black dark:text-white border border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            {{ !$user ? 'required' : '' }}>
        @error('password')
            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password Confirmation (hanya untuk create) -->
    @if(!$user)
    <div class="mb-8">
        <label for="password_confirmation" class="block text-sm font-medium text-zinc-800 dark:text-zinc-300 mb-2">
            Konfirmasi Password
        </label>
        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            class="block w-full bg-zinc-200 dark:bg-zinc-800 border text-black dark:text-white border-zinc-700 focus:border-orange-500 focus:ring-orange-500 rounded-2xl py-3.5 px-5 text-base placeholder-zinc-500"
            required>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-zinc-800">
        <a href="{{ route('users.index') }}"
           class="px-6 py-3.5 bg-zinc-800 hover:bg-zinc-700 dark:text-zinc-300 font-medium rounded-2xl transition-all text-center order-2 sm:order-1">
            Batal
        </a>
       
        <button type="submit"
                class="px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl transition-all shadow-lg shadow-orange-500/30 order-1 sm:order-2">
            {{ $user ? 'Update User' : 'Simpan User Baru' }}
        </button>
    </div>
</form>