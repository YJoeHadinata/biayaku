<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Resep untuk Produk: ') . $product->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('recipes.update', $recipe) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="material_id" class="block text-sm font-medium text-gray-700">Material</label>
                            <select name="material_id" id="material_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="">Pilih Material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id', $recipe->material_id) == $material->id ? 'selected' : '' }}>
                                        {{ $material->nama }} ({{ $material->unit }} - Rp
                                        {{ number_format($material->harga_satuan, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_takaran" class="block text-sm font-medium text-gray-700">Jumlah Takaran
                                per {{ $product->unit_output }}</label>
                            <input type="number" name="jumlah_takaran" id="jumlah_takaran"
                                value="{{ old('jumlah_takaran', $recipe->jumlah_takaran) }}" step="0.0001"
                                min="0.0001"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            <p class="mt-1 text-sm text-gray-600">
                                Masukkan jumlah dalam unit dasar material (jika ada konversi) atau unit harga satuan.
                            </p>
                            @error('jumlah_takaran')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('catatan', $recipe->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('products.recipes.index', $product) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-check-circle"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
