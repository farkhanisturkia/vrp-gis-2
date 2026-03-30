<x-app-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl text-white font-semibold">Manajemen Users</h1>
                <p class="text-zinc-400 text-sm">Kelola akun pengguna</p>
            </div>

            <a href="{{ route('users.index') }}?modal=create"
            class="bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded-xl text-sm">
            + User
            </a>
        </div>

        <!-- Table -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[900px]">

                    <table class="min-w-full whitespace-nowrap divide-y divide-zinc-800">

                        <thead class="bg-zinc-950">
                            <tr>
                                <th class="px-6 py-3 text-xs text-zinc-400">Nama</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Email</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Role</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Updated</th>
                                <th class="px-6 py-3 text-xs text-zinc-400 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-800">
                            @foreach ($users as $user)
                            <tr class="hover:bg-zinc-800/40">
                                <td class="px-6 py-3 text-white">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-zinc-300 truncate max-w-[200px]">{{ $user->email }}</td>
                                <td class="px-6 py-3">
                                    <span class="text-xs px-3 py-1 rounded-full
                                        {{ $user->role === 'admin' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-blue-500/20 text-blue-400' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-zinc-400 text-sm">
                                    {{ $user->updated_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <a href="?modal=edit&user_id={{ $user->id }}" class="text-orange-400">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy',$user->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-400 ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-6 py-3 border-t border-zinc-800">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if (request()->has('modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-zinc-900 rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            
            <div class="px-8 py-6 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">
                    {{ request('modal') === 'create' ? 'Tambah User Baru' : 'Edit User' }}
                </h3>
                <a href="{{ route('users.index') }}" 
                   class="text-zinc-400 hover:text-white text-2xl leading-none">×</a>
            </div>

            <div class="p-8">
                @if (request('modal') === 'create')
                    @include('users.form', ['user' => null])
                @elseif (request('modal') === 'edit')
                    @include('users.form', ['user' => $editUser])
                @endif
            </div>
        </div>
    </div>
    @endif
</x-app-layout>