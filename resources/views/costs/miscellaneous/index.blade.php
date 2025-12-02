<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Biaya Lain-lain') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium">Daftar Biaya Lain-lain</h3>
                            <p class="text-sm text-gray-600">Total: Rp {{ number_format($totalCosts, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('miscellaneous.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="bi bi-plus-circle"></i> Tambah Biaya Lain-lain
                        </a>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="cost_account_id"
                                    class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="cost_account_id" id="cost_account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($costAccounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ request('cost_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tanggal_dari" class="block text-sm font-medium text-gray-700">Tanggal
                                    Dari</label>
                                <input type="date" name="tanggal_dari" id="tanggal_dari"
                                    value="{{ request('tanggal_dari') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('miscellaneous.index') }}"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">Kategori</th>
                                    <th class="px-4 py-2 text-left">Nominal</th>
                                    <th class="px-4 py-2 text-left">Deskripsi</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costs as $cost)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $cost->tanggal->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">{{ $cost->costAccount->nama }}</td>
                                        <td class="px-4 py-2">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">{{ $cost->deskripsi ?: '-' }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('miscellaneous.edit', $cost) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('miscellaneous.destroy', $cost) }}"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus biaya lain-lain ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada biaya
                                            lain-lain ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $costs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
