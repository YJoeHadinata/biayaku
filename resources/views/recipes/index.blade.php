<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resep untuk Produk: ') . $product->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium">Daftar Resep</h3>
                            <p class="text-sm text-gray-600">HPP Preview: Rp
                                {{ number_format($hppPreview, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('products.recipes.create', $product) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                <i class="bi bi-plus-circle"></i> Tambah Resep
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
                                    <th class="px-4 py-2 text-left">Material</th>
                                    <th class="px-4 py-2 text-left">Unit</th>
                                    <th class="px-4 py-2 text-left">Takaran</th>
                                    <th class="px-4 py-2 text-left">Harga Satuan</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                    <th class="px-4 py-2 text-left">Catatan</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recipes as $recipe)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $recipe->material->nama }}</td>
                                        <td class="px-4 py-2">{{ $recipe->material->unit }}</td>
                                        <td class="px-4 py-2">{{ $recipe->jumlah_takaran }}</td>
                                        <td class="px-4 py-2">Rp
                                            {{ number_format($recipe->material->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">Rp
                                            {{ number_format($recipe->calculated_cost ?? $recipe->jumlah_takaran * $recipe->material->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2">{{ $recipe->catatan ?: '-' }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('recipes.edit', $recipe) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus resep ini?')">
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
                                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">Tidak ada resep
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
