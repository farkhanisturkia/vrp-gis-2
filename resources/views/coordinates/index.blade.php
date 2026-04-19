<x-app-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Manajemen Coordinates</h1>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">Kelola titik lokasi</p>
            </div>

            <a href="{{ route('coordinates.index') }}?modal=create"
               class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-2xl text-sm font-medium inline-flex items-center gap-2 justify-center">
                + Tambah Coordinate
            </a>
        </div>

        <!-- Table -->
        <div class="border border-zinc-400 dark:border-zinc-800 rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] divide-y divide-zinc-800">

                    <!-- HEADER -->
                    <thead class="bg-zinc-500/80 dark:bg-zinc-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Area</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Latitude</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Longitude</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-black dark:text-zinc-400">Updated</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-black dark:text-zinc-400">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="divide-y divide-zinc-800 bg-zinc-300 dark:bg-zinc-900">
                        @foreach ($coordinates as $c)
                        <tr class="hover:bg-zinc-800/60 transition-colors">

                            <td class="px-6 py-4 text-black dark:text-white font-medium">
                                {{ $c->area }}
                            </td>

                            <td class="px-6 py-4 font-mono text-black/90 dark:text-zinc-300">
                                {{ number_format($c->lat, 6) }}
                            </td>

                            <td class="px-6 py-4 font-mono text-black/90 dark:text-zinc-300">
                                {{ number_format($c->long, 6) }}
                            </td>

                            <td class="px-6 py-4 text-black/90 dark:text-zinc-400 text-sm">
                                {{ $c->updated_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-4">
                                <a href="?modal=edit&coordinate_id={{ $c->id }}" 
                                   class="text-orange-700 dark:text-orange-400 hover:text-orange-500 font-medium">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('coordinates.destroy',$c->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button
                                        onclick="return confirm('Yakin ingin menghapus coordinate ini?')"
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
                {{ $coordinates->links() }}
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
                    {{ request('modal') === 'create' ? 'Tambah Coordinate Baru' : 'Edit Coordinate' }}
                </h3>
                <a href="{{ route('coordinates.index') }}" 
                   class="text-3xl leading-none text-zinc-800 dark:text-zinc-400 hover:text-white">×</a>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-8">
                @if (request('modal') === 'create')
                    @include('coordinates.form', ['coordinate' => null])
                @elseif (request('modal') === 'edit')
                    @include('coordinates.form', ['coordinate' => $editCoordinate])
                @endif
            </div>
        </div>
    </div>
    @endif
</x-app-layout>