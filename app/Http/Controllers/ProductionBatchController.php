<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Services\HPPCalculationService;
use Illuminate\Http\Request;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductionBatch::with('product')->where('branch_id', auth()->user()->getCurrentBranchId());

        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->where('tanggal_produksi', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->where('tanggal_produksi', '<=', $request->tanggal_sampai);
        }

        $batches = $query->orderBy('tanggal_produksi', 'desc')->paginate(10);
        $products = Product::where('branch_id', auth()->user()->getCurrentBranchId())->get();

        return view('production-batches.index', compact('batches', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('recipes.material')->where('branch_id', auth()->user()->getCurrentBranchId())->get();

        return view('production-batches.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah_output' => 'required|numeric|min:0.0001',
            'tanggal_produksi' => 'required|date|before_or_equal:today',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Check production batch limit for non-super-admin users
        if (!$user->isSuperAdmin()) {
            $currentBatchCount = ProductionBatch::where('branch_id', $user->getCurrentBranchId())->count();
            if ($user->hasExceededLimit('production_batches', $currentBatchCount)) {
                return redirect()->route('production-batches.index')->with('error', 'Anda telah mencapai batas maksimal batch produksi (' . $user->getUsageLimit('production_batches') . ' batch). Upgrade paket Anda untuk membuat lebih banyak batch produksi.');
            }
        }

        $product = Product::findOrFail($request->product_id);

        // Check if product has recipes
        if ($product->recipes()->count() == 0) {
            return back()->withErrors(['product_id' => 'Produk ini belum memiliki resep.'])->withInput();
        }

        $batch = ProductionBatch::create([
            'product_id' => $request->product_id,
            'jumlah_output' => $request->jumlah_output,
            'tanggal_produksi' => $request->tanggal_produksi,
            'biaya_tambahan' => $request->biaya_tambahan ?? 0,
            'harga_jual' => $request->harga_jual,
            'keterangan' => $request->keterangan,
            'branch_id' => $user->getCurrentBranchId(),
        ]);

        // Calculate HPP
        $hppService = new HPPCalculationService();
        $hppService->updateBatchHPP($batch);

        return redirect()->route('production-batches.show', $batch)->with('success', 'Batch produksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = ProductionBatch::with('product.recipes.material')->findOrFail($id);

        $hppService = new HPPCalculationService();
        $calculations = $hppService->calculateHPP($batch);

        return view('production-batches.show', compact('batch', 'calculations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = ProductionBatch::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        $products = Product::with('recipes.material')->where('branch_id', auth()->user()->getCurrentBranchId())->get();

        return view('production-batches.edit', compact('batch', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $batch = ProductionBatch::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah_output' => 'required|numeric|min:0.0001',
            'tanggal_produksi' => 'required|date|before_or_equal:today',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product has recipes
        if ($product->recipes()->count() == 0) {
            return back()->withErrors(['product_id' => 'Produk ini belum memiliki resep.'])->withInput();
        }

        $batch->update([
            'product_id' => $request->product_id,
            'jumlah_output' => $request->jumlah_output,
            'tanggal_produksi' => $request->tanggal_produksi,
            'biaya_tambahan' => $request->biaya_tambahan ?? 0,
            'harga_jual' => $request->harga_jual,
            'keterangan' => $request->keterangan,
        ]);

        // Recalculate HPP
        $hppService = new HPPCalculationService();
        $hppService->updateBatchHPP($batch);

        return redirect()->route('production-batches.show', $batch)->with('success', 'Batch produksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $batch = ProductionBatch::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        $batch->delete();

        return redirect()->route('production-batches.index')->with('success', 'Batch produksi berhasil dihapus.');
    }
}
