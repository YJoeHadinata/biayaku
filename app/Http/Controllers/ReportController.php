<?php

namespace App\Http\Controllers;

use App\Exports\CostReportExport;
use App\Models\MiscellaneousCost;
use App\Models\OperationalCost;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:hpp,operational,miscellaneous,summary',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $type = $request->type;

        $data = $this->getReportData($type, $startDate, $endDate);

        return view('reports.show', compact('data', 'type', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'type' => 'required|in:hpp,operational,miscellaneous,summary',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $type = $request->type;

        $fileName = "laporan-{$type}-{$startDate}-{$endDate}.xlsx";

        return Excel::download(new CostReportExport($type, $startDate, $endDate), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'type' => 'required|in:hpp,operational,miscellaneous,summary',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $type = $request->type;

        $data = $this->getReportData($type, $startDate, $endDate);

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'type', 'startDate', 'endDate'));
        $fileName = "laporan-{$type}-{$startDate}-{$endDate}.pdf";

        return $pdf->download($fileName);
    }

    private function getReportData($type, $startDate, $endDate)
    {
        // Convert date strings to include full day range for proper comparison
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';

        switch ($type) {
            case 'hpp':
                return ProductionBatch::with('product')
                    ->whereBetween('tanggal_produksi', [$startDateTime, $endDateTime])
                    ->orderBy('tanggal_produksi')
                    ->get();

            case 'operational':
                return OperationalCost::with('costAccount')
                    ->whereBetween('tanggal', [$startDateTime, $endDateTime])
                    ->orderBy('tanggal')
                    ->get()
                    ->groupBy(function ($cost) {
                        return $cost->costAccount->nama;
                    });

            case 'miscellaneous':
                return MiscellaneousCost::with('costAccount')
                    ->whereBetween('tanggal', [$startDateTime, $endDateTime])
                    ->orderBy('tanggal')
                    ->get()
                    ->groupBy(function ($cost) {
                        return $cost->costAccount->nama;
                    });

            case 'summary':
                $hpp = ProductionBatch::whereBetween('tanggal_produksi', [$startDateTime, $endDateTime])
                    ->sum('total_hpp_dengan_tambahan');

                $operational = OperationalCost::whereBetween('tanggal', [$startDateTime, $endDateTime])
                    ->sum('nominal');

                $miscellaneous = MiscellaneousCost::whereBetween('tanggal', [$startDateTime, $endDateTime])
                    ->sum('nominal');

                return [
                    'hpp_total' => $hpp,
                    'operational_total' => $operational,
                    'miscellaneous_total' => $miscellaneous,
                    'grand_total' => $hpp + $operational + $miscellaneous,
                    'hpp_batches' => ProductionBatch::with('product')
                        ->whereBetween('tanggal_produksi', [$startDateTime, $endDateTime])
                        ->orderBy('tanggal_produksi')
                        ->get(),
                    'operational_costs' => OperationalCost::with('costAccount')
                        ->whereBetween('tanggal', [$startDateTime, $endDateTime])
                        ->orderBy('tanggal')
                        ->get(),
                    'miscellaneous_costs' => MiscellaneousCost::with('costAccount')
                        ->whereBetween('tanggal', [$startDateTime, $endDateTime])
                        ->orderBy('tanggal')
                        ->get(),
                ];

            default:
                return collect();
        }
    }
}
