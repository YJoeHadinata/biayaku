<?php

namespace App\Http\Controllers;

use App\Models\MiscellaneousCost;
use App\Models\OperationalCost;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's costs
        $today = now()->toDateString();
        $hppToday = ProductionBatch::where('tanggal_produksi', $today)->sum('total_hpp_dengan_tambahan');
        $operationalToday = OperationalCost::where('tanggal', $today)->sum('nominal');
        $miscellaneousToday = MiscellaneousCost::where('tanggal', $today)->sum('nominal');
        $totalToday = $hppToday + $operationalToday + $miscellaneousToday;

        // This month's costs
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();
        $hppMonth = ProductionBatch::whereBetween('tanggal_produksi', [$startOfMonth, $endOfMonth])->sum('total_hpp_dengan_tambahan');
        $operationalMonth = OperationalCost::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('nominal');
        $miscellaneousMonth = MiscellaneousCost::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('nominal');
        $totalMonth = $hppMonth + $operationalMonth + $miscellaneousMonth;

        // Year-to-date costs
        $startOfYear = now()->startOfYear()->toDateString();
        $hppYtd = ProductionBatch::whereBetween('tanggal_produksi', [$startOfYear, $today])->sum('total_hpp_dengan_tambahan');
        $operationalYtd = OperationalCost::whereBetween('tanggal', [$startOfYear, $today])->sum('nominal');
        $miscellaneousYtd = MiscellaneousCost::whereBetween('tanggal', [$startOfYear, $today])->sum('nominal');
        $totalYtd = $hppYtd + $operationalYtd + $miscellaneousYtd;

        // Recent transactions (last 5)
        $recentBatches = ProductionBatch::with('product')
            ->orderBy('tanggal_produksi', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($batch) {
                return [
                    'type' => 'HPP',
                    'description' => 'Batch Produksi: ' . $batch->product->nama,
                    'amount' => $batch->total_hpp_dengan_tambahan,
                    'date' => $batch->tanggal_produksi,
                ];
            });

        $recentOperational = OperationalCost::with('costAccount')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($cost) {
                return [
                    'type' => 'Operasional',
                    'description' => $cost->costAccount->nama . ': ' . $cost->deskripsi,
                    'amount' => $cost->nominal,
                    'date' => $cost->tanggal,
                ];
            });

        $recentMiscellaneous = MiscellaneousCost::with('costAccount')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($cost) {
                return [
                    'type' => 'Lain-lain',
                    'description' => $cost->costAccount->nama . ': ' . $cost->deskripsi,
                    'amount' => $cost->nominal,
                    'date' => $cost->tanggal,
                ];
            });

        $recentTransactions = collect()
            ->merge($recentBatches)
            ->merge($recentOperational)
            ->merge($recentMiscellaneous)
            ->sortByDesc('date')
            ->take(5);

        // Chart data for last 30 days
        $chartData = $this->getChartData();

        return view('dashboard', compact(
            'totalToday',
            'totalMonth',
            'totalYtd',
            'hppToday',
            'operationalToday',
            'miscellaneousToday',
            'hppMonth',
            'operationalMonth',
            'miscellaneousMonth',
            'hppYtd',
            'operationalYtd',
            'miscellaneousYtd',
            'recentTransactions',
            'chartData'
        ));
    }

    private function getChartData()
    {
        $dates = [];
        $hppData = [];
        $operationalData = [];
        $miscellaneousData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[] = now()->subDays($i)->format('d/m');

            $hppData[] = ProductionBatch::where('tanggal_produksi', $date)->sum('total_hpp_dengan_tambahan');
            $operationalData[] = OperationalCost::where('tanggal', $date)->sum('nominal');
            $miscellaneousData[] = MiscellaneousCost::where('tanggal', $date)->sum('nominal');
        }

        return [
            'dates' => $dates,
            'hpp' => $hppData,
            'operational' => $operationalData,
            'miscellaneous' => $miscellaneousData,
        ];
    }
}
