<div class="space-y-6">
    @php
        $totalOperational = 0;
        foreach ($data as $costs) {
            $totalOperational += $costs->sum('nominal');
        }
    @endphp

    <div class="bg-green-50 p-4 rounded-lg">
        <h4 class="font-medium text-green-800 mb-2">Ringkasan Biaya Operasional</h4>
        <p class="text-green-700">Total Biaya Operasional: Rp {{ number_format($totalOperational, 0, ',', '.') }}</p>
    </div>

    @foreach ($data as $category => $costs)
        <div>
            <h4 class="text-lg font-medium mb-4">{{ $category }}</h4>
            <div class="bg-gray-50 p-2 mb-2 rounded">
                <span class="font-medium">Total {{ $category }}: Rp
                    {{ number_format($costs->sum('nominal'), 0, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left border">Tanggal</th>
                            <th class="px-4 py-2 text-left border">Nominal</th>
                            <th class="px-4 py-2 text-left border">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costs as $cost)
                            <tr class="border-t">
                                <td class="px-4 py-2 border">{{ $cost->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 border">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 border">{{ $cost->deskripsi ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-500 border">Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
