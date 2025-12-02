<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Material') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('materials.update', $material) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Material</label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $material->nama) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                            <select name="unit" id="unit"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="">Pilih Unit</option>
                                <option value="kg" {{ old('unit', $material->unit) == 'kg' ? 'selected' : '' }}>kg
                                </option>
                                <option value="liter" {{ old('unit', $material->unit) == 'liter' ? 'selected' : '' }}>
                                    liter</option>
                                <option value="pcs" {{ old('unit', $material->unit) == 'pcs' ? 'selected' : '' }}>pcs
                                </option>
                                <option value="gram" {{ old('unit', $material->unit) == 'gram' ? 'selected' : '' }}>
                                    gram</option>
                                <option value="ml" {{ old('unit', $material->unit) == 'ml' ? 'selected' : '' }}>ml
                                </option>
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="harga_satuan" class="block text-sm font-medium text-gray-700">Harga Satuan
                                (Rp)</label>
                            <input type="number" name="harga_satuan" id="harga_satuan"
                                value="{{ old('harga_satuan', $material->harga_satuan) }}" step="0.01"
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('harga_satuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="base_unit" class="block text-sm font-medium text-gray-700">Unit Dasar</label>
                            <select name="base_unit" id="base_unit"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Pilih Unit Dasar (Opsional)</option>
                                <option value="gram"
                                    {{ old('base_unit', $material->base_unit) == 'gram' ? 'selected' : '' }}>gram
                                </option>
                                <option value="kg"
                                    {{ old('base_unit', $material->base_unit) == 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="liter"
                                    {{ old('base_unit', $material->base_unit) == 'liter' ? 'selected' : '' }}>liter
                                </option>
                                <option value="ml"
                                    {{ old('base_unit', $material->base_unit) == 'ml' ? 'selected' : '' }}>ml</option>
                                <option value="pcs"
                                    {{ old('base_unit', $material->base_unit) == 'pcs' ? 'selected' : '' }}>pcs
                                </option>
                                <option value="pack"
                                    {{ old('base_unit', $material->base_unit) == 'pack' ? 'selected' : '' }}>pack
                                </option>
                                <option value="box"
                                    {{ old('base_unit', $material->base_unit) == 'box' ? 'selected' : '' }}>box
                                </option>
                                <option value="karton"
                                    {{ old('base_unit', $material->base_unit) == 'karton' ? 'selected' : '' }}>karton
                                </option>
                            </select>
                            @error('base_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="qty_per_unit" class="block text-sm font-medium text-gray-700">Qty per
                                Unit</label>
                            <input type="number" name="qty_per_unit" id="qty_per_unit"
                                value="{{ old('qty_per_unit', $material->qty_per_unit) }}" step="0.01"
                                min="0" placeholder="Contoh: 1000 (untuk kg ke gram)"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('qty_per_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('deskripsi', $material->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('materials.index') }}"
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
