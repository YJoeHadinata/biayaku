<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tambah Material Baru</h1>
                        <p class="text-gray-600 mt-1">Masukkan informasi material untuk produksi</p>
                    </div>
                    <a href="{{ route('materials.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium inline-flex items-center transition-colors duration-200">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-8">
                    <form method="POST" action="{{ route('materials.store') }}" class="space-y-6">
                        @csrf

                        <!-- Nama Material -->
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="bi bi-tag mr-2 text-blue-500"></i>
                                Nama Material
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan nama material..." required>
                            @error('nama')
                                <div class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Unit dan Harga -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit" class="block text-sm font-semibold text-gray-900 mb-2">
                                    <i class="bi bi-boxes mr-2 text-green-500"></i>
                                    Unit Utama
                                </label>
                                <select name="unit" id="unit"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    required>
                                    <option value="">Pilih Unit</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg - Kilogram
                                    </option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter - Liter
                                    </option>
                                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>pcs - Pieces
                                    </option>
                                    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>gram - Gram
                                    </option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ml - Milliliter
                                    </option>
                                </select>
                                @error('unit')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="harga_satuan" class="block text-sm font-semibold text-gray-900 mb-2">
                                    <i class="bi bi-cash mr-2 text-yellow-500"></i>
                                    Harga Satuan (Rp)
                                </label>
                                <input type="number" name="harga_satuan" id="harga_satuan"
                                    value="{{ old('harga_satuan') }}" step="0.01" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="0.00" required>
                                @error('harga_satuan')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit Konversi -->
                        <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                            <div class="flex items-center mb-4">
                                <i class="bi bi-arrow-repeat mr-2 text-purple-500"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Konversi Unit (Opsional)</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="base_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                        Unit Dasar
                                    </label>
                                    <select name="base_unit" id="base_unit"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Pilih Unit Dasar</option>
                                        <option value="gram" {{ old('base_unit') == 'gram' ? 'selected' : '' }}>gram
                                        </option>
                                        <option value="kg" {{ old('base_unit') == 'kg' ? 'selected' : '' }}>kg
                                        </option>
                                        <option value="liter" {{ old('base_unit') == 'liter' ? 'selected' : '' }}>
                                            liter</option>
                                        <option value="ml" {{ old('base_unit') == 'ml' ? 'selected' : '' }}>ml
                                        </option>
                                        <option value="pcs" {{ old('base_unit') == 'pcs' ? 'selected' : '' }}>pcs
                                        </option>
                                        <option value="pack" {{ old('base_unit') == 'pack' ? 'selected' : '' }}>pack
                                        </option>
                                        <option value="box" {{ old('box') == 'box' ? 'selected' : '' }}>box</option>
                                        <option value="karton" {{ old('base_unit') == 'karton' ? 'selected' : '' }}>
                                            karton</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="qty_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                        Qty per Unit
                                    </label>
                                    <input type="number" name="qty_per_unit" id="qty_per_unit"
                                        value="{{ old('qty_per_unit') }}" step="0.01" min="0"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Contoh: 1000 (untuk kg ke gram)">
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">
                                <i class="bi bi-info-circle mr-1"></i>
                                Contoh: Jika 1 kg = 1000 gram, maka isi "1000" dan pilih "gram" sebagai unit dasar
                            </p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="bi bi-textarea mr-2 text-indigo-500"></i>
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Tambahkan deskripsi atau catatan untuk material ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('materials.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="bi bi-check-circle-fill mr-2"></i>
                                Simpan Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
