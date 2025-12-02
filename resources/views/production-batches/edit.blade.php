<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Batch Produksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('production-batches.update', $batch) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="product_id" id="product_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id', $batch->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama }} ({{ $product->unit_output }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_output" class="block text-sm font-medium text-gray-700">Jumlah
                                Output (Unit Produk)</label>
                            <input type="number" name="jumlah_output" id="jumlah_output"
                                value="{{ old('jumlah_output', $batch->jumlah_output) }}" step="0.0001" min="0.0001"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            <p class="mt-1 text-sm text-gray-600">
                                Berapa banyak unit produk yang diproduksi dalam batch ini?<br>
                                Contoh: Jika resep untuk 1 pack kopi 100g, dan Anda buat 10 pack, maka 10.
                            </p>
                            @error('jumlah_output')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_produksi" class="block text-sm font-medium text-gray-700">Tanggal
                                Produksi</label>
                            <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                                value="{{ old('tanggal_produksi', $batch->tanggal_produksi->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('tanggal_produksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="biaya_tambahan" class="block text-sm font-medium text-gray-700">Biaya Tambahan
                                (Rp)</label>
                            <input type="number" name="biaya_tambahan" id="biaya_tambahan"
                                value="{{ old('biaya_tambahan', $batch->biaya_tambahan) }}" step="0.01"
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('biaya_tambahan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual per Unit
                                (Rp)</label>
                            <input type="number" name="harga_jual" id="harga_jual"
                                value="{{ old('harga_jual', $batch->harga_jual) }}" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="mt-1 text-sm text-gray-600">
                                Harga jual per unit produk (opsional, untuk menghitung margin keuntungan).
                            </p>
                            @error('harga_jual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('keterangan', $batch->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('production-batches.show', $batch) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-check-circle"></i> Update Batch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
