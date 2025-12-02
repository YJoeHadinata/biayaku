<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Batch Produksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium">Batch Produksi: {{ $batch->product->nama }}</h3>
                            <p class="text-sm text-gray-600">Tanggal: {{ $batch->tanggal_produksi->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('production-batches.edit', $batch) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('production-batches.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Batch Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800">Jumlah Unit Diproduksi</h4>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ rtrim(rtrim(number_format($batch->jumlah_output, 2, ',', '.'), '0'), ',') }}
                                {{ $batch->product->unit_output }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-800">Total Biaya Material</h4>
                            <p class="text-2xl font-bold text-green-900">Rp
                                {{ number_format($batch->hpp_total, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-800">HPP per Unit</h4>
                            <p class="text-2xl font-bold text-purple-900">Rp
                                {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}</p>
                            @if ($batch->biaya_tambahan > 0)
                                <p class="text-sm text-purple-700 mt-1">
                                    (Termasuk biaya tambahan)
                                </p>
                            @endif
                        </div>
                        @if ($batch->harga_jual)
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h4 class="font-medium text-orange-800">Harga Jual per Unit</h4>
                                <p class="text-2xl font-bold text-orange-900">Rp
                                    {{ number_format($batch->harga_jual, 0, ',', '.') }}</p>
                                @php
                                    $profitMargin = $batch->harga_jual - $batch->total_hpp_per_unit_final;
                                    $profitPercentage =
                                        $batch->total_hpp_per_unit_final > 0
                                            ? ($profitMargin / $batch->total_hpp_per_unit_final) * 100
                                            : 0;
                                @endphp
                                <p class="text-sm text-orange-700 mt-1">
                                    Margin: Rp {{ number_format($profitMargin, 0, ',', '.') }}
                                    ({{ number_format($profitPercentage, 1) }}%)
                                </p>
                            </div>
                        @endif
                    </div>

                    @if ($batch->keterangan)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-800 mb-2">Keterangan</h4>
                            <p class="text-gray-600">{{ $batch->keterangan }}</p>
                        </div>
                    @endif

                    <!-- HPP Breakdown -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-800 mb-4">Breakdown Perhitungan HPP</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-2 text-left border">Material</th>
                                        <th class="px-4 py-2 text-left border">Unit</th>
                                        <th class="px-4 py-2 text-left border">Takaran</th>
                                        <th class="px-4 py-2 text-left border">Harga Satuan</th>
                                        <th class="px-4 py-2 text-left border">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($calculations['breakdown'] as $item)
                                        <tr class="border-t">
                                            <td class="px-4 py-2 border">{{ $item['material_nama'] }}</td>
                                            <td class="px-4 py-2 border">{{ $item['material_unit'] }}</td>
                                            <td class="px-4 py-2 border">{{ $item['takaran'] }}</td>
                                            <td class="px-4 py-2 border">Rp
                                                {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 border">Rp
                                                {{ number_format($item['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($batch->biaya_tambahan > 0)
                                        <tr class="border-t bg-yellow-50">
                                            <td colspan="4" class="px-4 py-2 border font-medium">Biaya Tambahan</td>
                                            <td class="px-4 py-2 border font-medium">Rp
                                                {{ number_format($batch->biaya_tambahan, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-t bg-gray-50">
                                        <td colspan="4" class="px-4 py-2 border font-bold">Total HPP</td>
                                        <td class="px-4 py-2 border font-bold">Rp
                                            {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-2">Ringkasan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Total Biaya Material:</span> Rp
                                {{ number_format($batch->hpp_total, 0, ',', '.') }}
                            </div>
                            <div>
                                <span class="font-medium">Biaya Tambahan:</span> Rp
                                {{ number_format($batch->biaya_tambahan, 0, ',', '.') }}
                            </div>
                            <div>
                                <span class="font-medium">Biaya Material per Unit:</span> Rp
                                {{ number_format($batch->hpp_per_unit, 0, ',', '.') }}
                            </div>
                            <div>
                                <span class="font-medium">HPP per Unit (Total):</span> Rp
                                {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}
                                <br><small class="text-gray-600">(Material + Biaya Tambahan)</small>
                            </div>
                            @if ($batch->harga_jual)
                                <div>
                                    <span class="font-medium">Harga Jual per Unit:</span> Rp
                                    {{ number_format($batch->harga_jual, 0, ',', '.') }}
                                </div>
                                @php
                                    $totalRevenue = $batch->harga_jual * $batch->jumlah_output;
                                    $totalCost = $batch->total_hpp_dengan_tambahan;
                                    $totalProfit = $totalRevenue - $totalCost;
                                    $profitMargin = $totalCost > 0 ? ($totalProfit / $totalCost) * 100 : 0;
                                @endphp
                                <div>
                                    <span class="font-medium">Total Pendapatan:</span> Rp
                                    {{ number_format($totalRevenue, 0, ',', '.') }}
                                </div>
                                <div>
                                    <span class="font-medium">Total Laba:</span> Rp
                                    {{ number_format($totalProfit, 0, ',', '.') }}
                                    <span class="text-green-600">({{ number_format($profitMargin, 1) }}%)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
