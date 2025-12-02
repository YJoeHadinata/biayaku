<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucfirst($type) }} - {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .summary {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }

        .summary p {
            margin: 5px 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background: #e9ecef;
            font-weight: bold;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 25px 0 10px 0;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan {{ ucfirst($type) }}</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if ($type === 'hpp')
        <div class="summary">
            <h3>Ringkasan HPP Produksi</h3>
            <p>Total HPP: Rp {{ number_format($data->sum('total_hpp_dengan_tambahan'), 0, ',', '.') }}</p>
        </div>

        <div class="section-title">Detail Batch Produksi</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Produksi</th>
                    <th>Produk</th>
                    <th>Jumlah Output</th>
                    <th>Unit</th>
                    <th>HPP Total</th>
                    <th>HPP per Unit</th>
                    <th>Biaya Tambahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $batch)
                    <tr>
                        <td>{{ $batch->tanggal_produksi->format('d/m/Y') }}</td>
                        <td>{{ $batch->product->nama }}</td>
                        <td class="text-right">{{ number_format($batch->jumlah_output, 4, ',', '.') }}</td>
                        <td>{{ $batch->product->unit_output }}</td>
                        <td class="text-right">Rp {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}
                        </td>
                        <td class="text-right">Rp {{ number_format($batch->total_hpp_per_unit_final, 0, ',', '.') }}
                        </td>
                        <td class="text-right">Rp {{ number_format($batch->biaya_tambahan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada data batch produksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($type === 'operational')
        <div class="summary">
            <h3>Ringkasan Biaya Operasional</h3>
            <p>Total Biaya Operasional: Rp {{ number_format($data->flatten()->sum('nominal'), 0, ',', '.') }}</p>
        </div>

        @foreach ($data as $categoryName => $costs)
            <div class="section-title">Kategori: {{ $categoryName }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($costs as $cost)
                        <tr>
                            <td>{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $cost->deskripsi ?: '-' }}</td>
                            <td class="text-right">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2"><strong>Total {{ $categoryName }}</strong></td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($costs->sum('nominal'), 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @elseif($type === 'miscellaneous')
        <div class="summary">
            <h3>Ringkasan Biaya Lain-lain</h3>
            <p>Total Biaya Lain-lain: Rp {{ number_format($data->flatten()->sum('nominal'), 0, ',', '.') }}</p>
        </div>

        @foreach ($data as $categoryName => $costs)
            <div class="section-title">Kategori: {{ $categoryName }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($costs as $cost)
                        <tr>
                            <td>{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $cost->deskripsi ?: '-' }}</td>
                            <td class="text-right">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2"><strong>Total {{ $categoryName }}</strong></td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($costs->sum('nominal'), 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @elseif($type === 'summary')
        <div class="summary">
            <h3>Laporan Ringkas Keuangan</h3>
            <p>Total HPP Produksi: Rp {{ number_format($data['hpp_total'], 0, ',', '.') }}</p>
            <p>Total Biaya Operasional: Rp {{ number_format($data['operational_total'], 0, ',', '.') }}</p>
            <p>Total Biaya Lain-lain: Rp {{ number_format($data['miscellaneous_total'], 0, ',', '.') }}</p>
            <p><strong>Grand Total Pengeluaran: Rp {{ number_format($data['grand_total'], 0, ',', '.') }}</strong></p>
        </div>

        @if ($data['hpp_batches']->count() > 0)
            <div class="section-title">Detail HPP Produksi</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>HPP Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['hpp_batches'] as $batch)
                        <tr>
                            <td>{{ $batch->tanggal_produksi->format('d/m/Y') }}</td>
                            <td>{{ $batch->product->nama }}</td>
                            <td class="text-right">Rp
                                {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($data['operational_costs']->count() > 0)
            <div class="section-title">Detail Biaya Operasional</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['operational_costs'] as $cost)
                        <tr>
                            <td>{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $cost->costAccount->nama }}</td>
                            <td>{{ $cost->deskripsi ?: '-' }}</td>
                            <td class="text-right">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($data['miscellaneous_costs']->count() > 0)
            <div class="section-title">Detail Biaya Lain-lain</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['miscellaneous_costs'] as $cost)
                        <tr>
                            <td>{{ $cost->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $cost->costAccount->nama }}</td>
                            <td>{{ $cost->deskripsi ?: '-' }}</td>
                            <td class="text-right">Rp {{ number_format($cost->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
</body>

</html>
