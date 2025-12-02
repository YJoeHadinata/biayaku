<?php

namespace App\Http\Controllers;

use App\Models\CostAccount;
use App\Models\MiscellaneousCost;
use Illuminate\Http\Request;

class MiscellaneousCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MiscellaneousCost::with('costAccount');

        if ($request->has('cost_account_id') && $request->cost_account_id) {
            $query->where('cost_account_id', $request->cost_account_id);
        }

        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $costs = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Calculate summary
        $summaryQuery = MiscellaneousCost::query();

        if ($request->has('cost_account_id') && $request->cost_account_id) {
            $summaryQuery->where('cost_account_id', $request->cost_account_id);
        }

        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $summaryQuery->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $summaryQuery->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $totalCosts = $summaryQuery->sum('nominal');
        $costAccounts = CostAccount::where('tipe', 'miscellaneous')->get();

        return view('costs.miscellaneous.index', compact('costs', 'costAccounts', 'totalCosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $costAccounts = CostAccount::where('tipe', 'miscellaneous')->get();
        return view('costs.miscellaneous.create', compact('costAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cost_account_id' => 'required|exists:cost_accounts,id',
            'nominal' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'nullable|string',
        ]);

        MiscellaneousCost::create($request->only(['cost_account_id', 'nominal', 'tanggal', 'deskripsi']));

        return redirect()->route('miscellaneous.index')->with('success', 'Biaya lain-lain berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cost = MiscellaneousCost::findOrFail($id);
        $costAccounts = CostAccount::where('tipe', 'miscellaneous')->get();
        return view('costs.miscellaneous.edit', compact('cost', 'costAccounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cost = MiscellaneousCost::findOrFail($id);

        $request->validate([
            'cost_account_id' => 'required|exists:cost_accounts,id',
            'nominal' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'nullable|string',
        ]);

        $cost->update($request->only(['cost_account_id', 'nominal', 'tanggal', 'deskripsi']));

        return redirect()->route('miscellaneous.index')->with('success', 'Biaya lain-lain berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cost = MiscellaneousCost::findOrFail($id);
        $cost->delete();

        return redirect()->route('miscellaneous.index')->with('success', 'Biaya lain-lain berhasil dihapus.');
    }
}
