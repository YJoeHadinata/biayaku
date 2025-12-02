<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-medium text-blue-800">Total HPP</h4>
            <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($data['hpp_total'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <h4 class="font-medium text-green-800">Biaya Operasional</h4>
            <p class="text-2xl font-bold text-green-900">Rp {{ number_format($data['operational_total'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <h4 class="font-medium text-purple-800">Biaya Lain-lain</h4>
            <p class="text-2xl font-bold text-purple-900">Rp
                {{ number_format($data['miscellaneous_total'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <h4 class="font-medium text-red-800">Total Keseluruhan</h4>
            <p class="text-2xl font-bold text-red-900">Rp {{ number_format($data['grand_total'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- HPP Details -->
    <div>
        <h4 class="text-lg font-medium mb-4">Detail HPP Produksi</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left border">Tanggal</th>
                        <th class="px-4 py-2 text-left border">Produk</th>
                        <th class="px-4 py-2 text-left border">Output</th>
                        <th class="px-4 py-2 text-left border">HPP Total</th>
                        <th class="px-4 py-2 text-left border">HPP per Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['hpp_batches'] as $batch)
                        <tr class="border-t">
                            <td class="px-4 py-2 border">{{ $batch->tanggal_produksi->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $batch->product->nama }}</td>
                            <td class="px-4 py-2 border">{{ $batch->jumlah_output }} {{ $batch->product->unit_output }}
                            </td>
                            <td class="px-4 py-2 border">Rp
                                {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">Rp
                                {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500 border">Tidak ada data HPP
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Operational Costs -->
    <div>
        <h4 class="text-lg font-medium mb-4">Detail Biaya Operasional</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left border">Tanggal</th>
                        <th class="px-4 py-2 text-left border">Kategori</th>
                        <th class="px-4 py-2 text-left border">Nominal</th>
                        <th class="px-4 py-2 text-left border">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['operational_costs'] as $cost)
                        <tr class="border-t">
                            <td class="px-4 py-2 border">{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $cost->costAccount->nama }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $cost->deskripsi ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500 border">Tidak ada biaya
                                operasional</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Miscellaneous Costs -->
    <div>
        <h4 class="text-lg font-medium mb-4">Detail Biaya Lain-lain</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left border">Tanggal</th>
                        <th class="px-4 py-2 text-left border">Kategori</th>
                        <th class="px-4 py-2 text-left border">Nominal</th>
                        <th class="px-4 py-2 text-left border">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['miscellaneous_costs'] as $cost)
                        <tr class="border-t">
                            <td class="px-4 py-2 border">{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $cost->costAccount->nama }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $cost->deskripsi ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500 border">Tidak ada biaya
                                lain-lain</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
