<div class="space-y-6">
    <div class="bg-blue-50 p-4 rounded-lg">
        <h4 class="font-medium text-blue-800 mb-2">Ringkasan HPP Produksi</h4>
        <p class="text-blue-700">Total HPP: Rp {{ number_format($data->sum('total_hpp_dengan_tambahan'), 0, ',', '.') }}
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left border">Tanggal Produksi</th>
                    <th class="px-4 py-2 text-left border">Produk</th>
                    <th class="px-4 py-2 text-left border">Jumlah Output</th>
                    <th class="px-4 py-2 text-left border">Unit</th>
                    <th class="px-4 py-2 text-left border">HPP Total</th>
                    <th class="px-4 py-2 text-left border">HPP per Unit</th>
                    <th class="px-4 py-2 text-left border">Biaya Tambahan</th>
                    <th class="px-4 py-2 text-left border">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $batch)
                    <tr class="border-t">
                        <td class="px-4 py-2 border">{{ $batch->tanggal_produksi->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">{{ $batch->product->nama }}</td>
                        <td class="px-4 py-2 border">{{ $batch->jumlah_output }}</td>
                        <td class="px-4 py-2 border">{{ $batch->product->unit_output }}</td>
                        <td class="px-4 py-2 border">Rp
                            {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border">Rp
                            {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border">Rp {{ number_format($batch->biaya_tambahan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border">{{ $batch->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center text-gray-500 border">Tidak ada data batch
                            produksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
