<?php

namespace App\Exports;

use App\Models\MiscellaneousCost;
use App\Models\OperationalCost;
use App\Models\ProductionBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class CostReportExport implements WithMultipleSheets
{
    protected $type;
    protected $startDate;
    protected $endDate;

    public function __construct($type, $startDate, $endDate)
    {
        $this->type = $type;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->type === 'summary' || $this->type === 'hpp') {
            $sheets[] = new HppSheet($this->startDate, $this->endDate);
        }

        if ($this->type === 'summary' || $this->type === 'operational') {
            $sheets[] = new OperationalSheet($this->startDate, $this->endDate);
        }

        if ($this->type === 'summary' || $this->type === 'miscellaneous') {
            $sheets[] = new MiscellaneousSheet($this->startDate, $this->endDate);
        }

        if ($this->type === 'summary') {
            $sheets[] = new SummarySheet($this->startDate, $this->endDate);
        }

        return $sheets;
    }
}

class HppSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'HPP Produksi';
    }

    public function headings(): array
    {
        return [
            'Tanggal Produksi',
            'Produk',
            'Jumlah Output',
            'Unit',
            'HPP Total',
            'HPP per Unit',
            'Biaya Tambahan',
            'Total HPP Final',
            'Keterangan'
        ];
    }

    public function collection()
    {
        return ProductionBatch::with('product')
            ->whereBetween('tanggal_produksi', [$this->startDate, $this->endDate])
            ->orderBy('tanggal_produksi')
            ->get()
            ->map(function ($batch) {
                return [
                    $batch->tanggal_produksi->format('d/m/Y'),
                    $batch->product->nama,
                    $batch->jumlah_output,
                    $batch->product->unit_output,
                    $batch->hpp_total,
                    $batch->hpp_per_unit,
                    $batch->biaya_tambahan,
                    $batch->total_hpp_per_unit_final,
                    $batch->keterangan,
                ];
            });
    }
}

class OperationalSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Biaya Operasional';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Nominal',
            'Deskripsi'
        ];
    }

    public function collection()
    {
        return OperationalCost::with('costAccount')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($cost) {
                return [
                    $cost->tanggal->format('d/m/Y'),
                    $cost->costAccount->nama,
                    $cost->nominal,
                    $cost->deskripsi,
                ];
            });
    }
}

class MiscellaneousSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Biaya Lain-lain';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Nominal',
            'Deskripsi'
        ];
    }

    public function collection()
    {
        return MiscellaneousCost::with('costAccount')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($cost) {
                return [
                    $cost->tanggal->format('d/m/Y'),
                    $cost->costAccount->nama,
                    $cost->nominal,
                    $cost->deskripsi,
                ];
            });
    }
}

class SummarySheet implements FromCollection, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function collection()
    {
        $hpp = ProductionBatch::whereBetween('tanggal_produksi', [$this->startDate, $this->endDate])->sum('total_hpp_dengan_tambahan');
        $operational = OperationalCost::whereBetween('tanggal', [$this->startDate, $this->endDate])->sum('nominal');
        $miscellaneous = MiscellaneousCost::whereBetween('tanggal', [$this->startDate, $this->endDate])->sum('nominal');

        return collect([
            ['Kategori', 'Total'],
            ['HPP Produksi', $hpp],
            ['Biaya Operasional', $operational],
            ['Biaya Lain-lain', $miscellaneous],
            ['Total Keseluruhan', $hpp + $operational + $miscellaneous],
        ]);
    }
}
