<x-app-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl text-white font-semibold">Manajemen Coordinates</h1>
                <p class="text-zinc-400 text-sm">Kelola titik lokasi</p>
            </div>

            <a href="?modal=create"
            class="bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded-xl text-sm">
                + Coordinate
            </a>
        </div>

        <!-- Table -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[1100px]">

                    <table class="min-w-full whitespace-nowrap divide-y divide-zinc-800">

                        <!-- HEADER -->
                        <thead class="bg-zinc-950">
                            <tr>
                                <th class="px-6 py-3 text-xs text-zinc-400">Area</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Latitude</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Longitude</th>
                                <th class="px-6 py-3 text-xs text-zinc-400">Updated</th>
                                <th class="px-6 py-3 text-xs text-zinc-400 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody class="divide-y divide-zinc-800">
                            @foreach ($coordinates as $c)
                            <tr class="hover:bg-zinc-800/40 transition">

                                <td class="px-6 py-3">
                                    <div class="text-white font-medium">{{ $c->area }}</div>
                                </td>

                                <td class="px-6 py-3 font-mono text-zinc-300">
                                    {{ number_format($c->lat, 6) }}
                                </td>

                                <td class="px-6 py-3 font-mono text-zinc-300">
                                    {{ number_format($c->long, 6) }}
                                </td>

                                <td class="px-6 py-3 text-zinc-400 text-sm">
                                    {{ $c->updated_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-6 py-3 text-right whitespace-nowrap space-x-3">
                                    <a href="?modal=edit&coordinate_id={{ $c->id }}"
                                    class="text-orange-400 hover:text-orange-300 text-sm">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('coordinates.destroy',$c->id) }}" class="inline">
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
                {{ $coordinates->links() }}
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
                    {{ request('modal') === 'create' ? 'Tambah Coordinate Baru' : 'Edit Coordinate' }}
                </h3>
                <a href="{{ route('coordinates.index') }}" 
                   class="text-zinc-400 hover:text-white text-3xl leading-none">×</a>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                @if (request('modal') === 'create')
                    @include('coordinates.form', ['coordinate' => null])
                @elseif (request('modal') === 'edit' && request('coordinate_id'))
                    @php $editCoordinate = \App\Models\Coordinate::find(request('coordinate_id')); @endphp
                    @if($editCoordinate)
                        @include('coordinates.form', ['coordinate' => $editCoordinate])
                    @else
                        <p class="text-red-400">Coordinate tidak ditemukan</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif
</x-app-layout>