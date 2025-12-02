<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variations = $product->variations;

        return view('product-variations.index', compact('product', 'variations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);

        return view('product-variations.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_tambahan' => 'required|numeric|min:0',
            'multiplier' => 'required|numeric|min:0.1',
            'deskripsi' => 'nullable|string',
        ]);

        ProductVariation::create([
            'product_id' => $productId,
            'nama' => $request->nama,
            'harga_tambahan' => $request->harga_tambahan,
            'multiplier' => $request->multiplier,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('products.variations.index', $productId)->with('success', 'Variasi produk berhasil ditambahkan.');
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
        $variation = ProductVariation::with('product')->findOrFail($id);
        $product = $variation->product;

        return view('product-variations.edit', compact('product', 'variation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $productId = $variation->product_id;

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_tambahan' => 'required|numeric|min:0',
            'multiplier' => 'required|numeric|min:0.1',
            'deskripsi' => 'nullable|string',
        ]);

        $variation->update([
            'nama' => $request->nama,
            'harga_tambahan' => $request->harga_tambahan,
            'multiplier' => $request->multiplier,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('products.variations.index', $productId)->with('success', 'Variasi produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $productId = $variation->product_id;
        $variation->delete();

        return redirect()->route('products.variations.index', $productId)->with('success', 'Variasi produk berhasil dihapus.');
    }
}
