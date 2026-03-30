<x-app-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl text-white font-semibold">Manajemen Armada</h1>
                <p class="text-zinc-400 text-sm">Kelola kendaraan</p>
            </div>

            <a href="?modal=create"
            class="bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded-xl text-sm">
                + Armada
            </a>
        </div>

        <!-- Table -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[1000px]">

                    <table class="min-w-full whitespace-nowrap divide-y divide-zinc-800">

                        <!-- HEADER -->
                        <thead class="bg-zinc-950">
                            <tr>
                                <th class="px-6 py-3 text-xs text-zinc-400">Nama</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Kapasitas</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">No. Plat</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Updated</th>
                                <th class="px-6 py-3 text-xs text-zinc-400 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody class="divide-y divide-zinc-800">
                            @foreach ($armadas as $a)
                            <tr class="hover:bg-zinc-800/40 transition">

                                <td class="px-6 py-3">
                                    <div class="text-white font-medium">{{ $a->name }}</div>
                                </td>

                                <td class="px-6 py-3 text-zinc-300">
                                    {{ $a->capacity }} 
                                    <span class="text-zinc-500 text-sm">KG</span>
                                </td>

                                <td class="px-6 py-3">
                                    <span class="font-mono text-orange-400">
                                        {{ $a->no_plat }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-zinc-400 text-sm">
                                    {{ $a->updated_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-6 py-3 text-right whitespace-nowrap space-x-3">
                                    <a href="?modal=edit&armada_id={{ $a->id }}"
                                    class="text-orange-400 hover:text-orange-300 text-sm">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('armadas.destroy',$a->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-400 hover:text-red-500 text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-zinc-800">
                {{ $armadas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if (request()->has('modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-zinc-900 rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">
                    {{ request('modal') === 'create' ? 'Tambah Armada Baru' : 'Edit Armada' }}
                </h3>
                <a href="{{ route('armadas.index') }}" 
                   class="text-zinc-400 hover:text-white text-3xl leading-none">×</a>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                @if (request('modal') === 'create')
                    @include('armadas.form', ['armada' => null])
                @elseif (request('modal') === 'edit' && request('armada_id'))
                    @php $editArmada = \App\Models\Armada::find(request('armada_id')); @endphp
                    @if($editArmada)
                        @include('armadas.form', ['armada' => $editArmada])
                    @else
                        <p class="text-red-400">Armada tidak ditemukan</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif
</x-app-layout>