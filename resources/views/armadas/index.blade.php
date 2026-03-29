<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tombol Tambah Armada -->
            <div class="mb-6 flex justify-end">
                <a href="{{ route('armadas.index') }}?modal=create"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition duration-150 ease-in-out flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create
                </a>
            </div>

            <!-- Notifikasi sukses / error -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabel Armadas -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Plat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($armadas as $armada)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $armada->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $armada->capacity }} KG</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-mono">{{ $armada->no_plat }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $armada->updated_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                        <a href="{{ route('armadas.index') }}?modal=edit&armada_id={{ $armada->id }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <form action="{{ route('armadas.destroy', $armada->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure?')"
                                                    class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        No armadas found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 px-6 pb-6">
                    {{ $armadas->links() }}
                </div>
            </div>

            <!-- Modal Container -->
            @if (request()->has('modal'))
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">

                        <!-- Header Modal -->
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ request('modal') === 'create' ? 'Add New Armada Form' : 'Edit Armada Form' }}
                            </h3>
                        </div>

                        <!-- Body Modal -->
                        <div class="p-6">

                            @if (request('modal') === 'create')
                                <form action="{{ route('armadas.store') }}" method="POST">
                                    @csrf

                                    <!-- Name -->
                                    <div class="mb-5">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                               class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                                               required>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Capacity -->
                                    <div class="mb-5">
                                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (kg)</label>
                                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}"
                                               min="1" class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacity') border-red-300 @enderror"
                                               required>
                                        @error('capacity')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- No Plat -->
                                    <div class="mb-5">
                                        <label for="no_plat" class="block text-sm font-medium text-gray-700 mb-1">No Plat</label>
                                        <input type="text" name="no_plat" id="no_plat" value="{{ old('no_plat') }}"
                                               class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('no_plat') border-red-300 @enderror"
                                               required>
                                        @error('no_plat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-8 flex justify-end gap-3">
                                        <a href="{{ route('armadas.index') }}"
                                           class="px-4 py-2 bg-white border rounded-md text-gray-700 hover:bg-gray-50">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            Save
                                        </button>
                                    </div>
                                </form>

                            @elseif (request('modal') === 'edit' && request('armada_id'))
                                @php
                                    $editArmada = \App\Models\Armada::find(request('armada_id'));
                                @endphp

                                @if($editArmada)
                                    <form action="{{ route('armadas.update', $editArmada->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Name -->
                                        <div class="mb-5">
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $editArmada->name) }}"
                                                   class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                                                   required>
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Capacity -->
                                        <div class="mb-5">
                                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (kg)</label>
                                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $editArmada->capacity) }}"
                                                   min="1" class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacity') border-red-300 @enderror"
                                                   required>
                                            @error('capacity')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- No Plat -->
                                        <div class="mb-5">
                                            <label for="no_plat" class="block text-sm font-medium text-gray-700 mb-1">No Plat</label>
                                            <input type="text" name="no_plat" id="no_plat" value="{{ old('no_plat', $editArmada->no_plat) }}"
                                                   class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('no_plat') border-red-300 @enderror"
                                                   required>
                                            @error('no_plat')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mt-8 flex justify-end gap-3">
                                            <a href="{{ route('armadas.index') }}"
                                               class="px-4 py-2 bg-white border rounded-md text-gray-700 hover:bg-gray-50">
                                                Cancel
                                            </a>
                                            <button type="submit"
                                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-red-600">Armada not found</p>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>