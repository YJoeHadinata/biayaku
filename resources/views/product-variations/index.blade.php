<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Variasi untuk Produk: ') . $product->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Variasi</h3>
                        <div>
                            <a href="{{ route('products.variations.create', $product) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                <i class="bi bi-plus-circle"></i> Tambah Variasi
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-arrow-left"></i> Kembali ke Produk
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Nama Variasi</th>
                                    <th class="px-4 py-2 text-left">Harga Tambahan</th>
                                    <th class="px-4 py-2 text-left">Multiplier</th>
                                    <th class="px-4 py-2 text-left">Deskripsi</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($variations as $variation)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $variation->id }}</td>
                                        <td class="px-4 py-2">{{ $variation->nama }}</td>
                                        <td class="px-4 py-2">Rp
                                            {{ number_format($variation->harga_tambahan, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">{{ $variation->multiplier }}x</td>
                                        <td class="px-4 py-2">{{ $variation->deskripsi ?: '-' }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('variations.edit', $variation) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('variations.destroy', $variation) }}"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus variasi ini?')">
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
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada variasi
                                            ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
