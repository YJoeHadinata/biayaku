<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\HPPCalculationService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('recipes.material')->where('branch_id', auth()->user()->getCurrentBranchId());

        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);

        // Calculate material costs for each product
        $hppService = new HPPCalculationService();
        foreach ($products as $product) {
            $product->material_cost_per_unit = $hppService->calculateHPPPreview($product, 1)['hpp_per_unit'];
        }

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'unit_output' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Check product limit for non-super-admin users
        if (!$user->isSuperAdmin()) {
            $currentProductCount = Product::where('branch_id', $user->getCurrentBranchId())->count();
            if ($user->hasExceededLimit('products', $currentProductCount)) {
                return redirect()->route('products.index')->with('error', 'Anda telah mencapai batas maksimal produk (' . $user->getUsageLimit('products') . ' produk). Upgrade paket Anda untuk membuat lebih banyak produk.');
            }
        }

        Product::create($request->only(['nama', 'unit_output', 'deskripsi']) + ['branch_id' => $user->getCurrentBranchId()]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
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
        $product = Product::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'unit_output' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $product->update($request->only(['nama', 'unit_output', 'deskripsi']));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
