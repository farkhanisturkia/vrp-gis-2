<x-app-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Manajemen Armada</h1>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">Kelola kendaraan</p>
            </div>

            <a href="{{ route('armadas.index') }}?modal=create"
               class="bg-orange-500 hover:bg-orange-600 text-zinc-800 dark:text-white px-6 py-3 rounded-2xl text-sm font-medium inline-flex items-center gap-2 justify-center">
                + Tambah Armada
            </a>
        </div>

        <!-- Table -->
        <div class="border border-zinc-400 dark:border-zinc-800 rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] divide-y divide-zinc-800">
                    
                    <!-- HEADER -->
                    <thead class="bg-zinc-500/80 dark:bg-zinc-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400"">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Kapasitas</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">No. Plat</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Updated</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-black dark:text-zinc-400">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="divide-y divide-zinc-800 bg-zinc-300 dark:bg-zinc-900">
                        @foreach ($armadas as $a)
                        <tr class="hover:bg-zinc-800/60 transition-colors">

                            <td class="px-6 py-4 text-black dark:text-white font-medium">
                                {{ $a->name }}
                            </td>

                            <td class="px-6 py-4 text-black/90 dark:text-zinc-300">
                                {{ $a->capacity }} KG
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-mono text-orange-700 dark:text-orange-400">
                                    {{ $a->no_plat }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-black/90 dark:text-zinc-400 text-sm">
                                {{ $a->updated_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-4">
                                <a href="?modal=edit&armada_id={{ $a->id }}" 
                                   class="text-orange-700 dark:text-orange-400 hover:text-orange-500 font-medium">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('armadas.destroy',$a->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button
                                        onclick="return confirm('Yakin ingin menghapus armada ini?')"
                                        class="text-red-700 dark:text-red-400 hover:text-red-500 font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-zinc-800">
                {{ $armadas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if (request()->has('modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 dark:bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-zinc-400 dark:bg-zinc-900 rounded-3xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-zinc-800 dark:text-zinc-400">
                    {{ request('modal') === 'create' ? 'Tambah Armada Baru' : 'Edit Armada' }}
                </h3>
                <a href="{{ route('armadas.index') }}" 
                   class="text-3xl leading-none text-zinc-800 dark:text-zinc-400 hover:text-white">×</a>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-8">
                @if (request('modal') === 'create')
                    @include('armadas.form', ['armada' => null])
                @elseif (request('modal') === 'edit')
                    @include('armadas.form', ['armada' => $editArmada])
                @endif
            </div>
        </div>
    </div>
    @endif
</x-app-layout>