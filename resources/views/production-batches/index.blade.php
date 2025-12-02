<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch Produksi & Perhitungan HPP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Batch Produksi</h3>
                        <a href="{{ route('production-batches.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="bi bi-plus-circle"></i> Tambah Batch Produksi
                        </a>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                                <select name="product_id" id="product_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->nama }}
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
                            <div>
                                <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal
                                    Sampai</label>
                                <input type="date" name="tanggal_sampai" id="tanggal_sampai"
                                    value="{{ request('tanggal_sampai') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('production-batches.index') }}"
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
                                    <th class="px-4 py-2 text-left">Produk</th>
                                    <th class="px-4 py-2 text-left">Jumlah Unit</th>
                                    <th class="px-4 py-2 text-left">Tanggal Produksi</th>
                                    <th class="px-4 py-2 text-left">Total Biaya Material</th>
                                    <th class="px-4 py-2 text-left">HPP per Unit</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batches as $batch)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $batch->product->nama }}</td>
                                        <td class="px-4 py-2">
                                            {{ rtrim(rtrim(number_format($batch->jumlah_output, 2, ',', '.'), '0'), ',') }}
                                            {{ $batch->product->unit_output }}</td>
                                        <td class="px-4 py-2">{{ $batch->tanggal_produksi->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">Rp {{ number_format($batch->hpp_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2">Rp
                                            {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('production-batches.show', $batch) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('production-batches.edit', $batch) }}"
                                                class="text-green-600 hover:text-green-900 mr-2">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST"
                                                action="{{ route('production-batches.destroy', $batch) }}"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus batch produksi ini?')">
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
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada batch
                                            produksi ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $batches->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
