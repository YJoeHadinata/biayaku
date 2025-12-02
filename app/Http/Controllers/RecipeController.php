<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\Recipe;
use App\Services\HPPCalculationService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $recipes = $product->recipes()->with('material')->get();

        // Calculate HPP preview using the service
        $hppService = new HPPCalculationService();
        $hppPreviewData = $hppService->calculateHPPPreview($product, 1);
        $hppPreview = $hppPreviewData['hpp_total'];

        // Add calculated material cost to each recipe for display
        $recipesWithCosts = $recipes->map(function ($recipe) use ($hppService) {
            $recipe->calculated_cost = $hppService->calculateMaterialCost($recipe);
            return $recipe;
        });

        return view('recipes.index', compact('product', 'recipes', 'hppPreview', 'recipesWithCosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $materials = Material::all();

        return view('recipes.create', compact('product', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'jumlah_takaran' => 'required|numeric|min:0.0001',
            'catatan' => 'nullable|string',
        ]);

        // Check unique product + material
        $exists = Recipe::where('product_id', $productId)
            ->where('material_id', $request->material_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['material_id' => 'Material ini sudah ada dalam resep produk ini.'])->withInput();
        }

        Recipe::create([
            'product_id' => $productId,
            'material_id' => $request->material_id,
            'jumlah_takaran' => $request->jumlah_takaran,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('products.recipes.index', $productId)->with('success', 'Resep berhasil ditambahkan.');
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
        $recipe = Recipe::with('product')->findOrFail($id);
        $product = $recipe->product;
        $materials = Material::all();

        return view('recipes.edit', compact('product', 'recipe', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $productId = $recipe->product_id;

        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'jumlah_takaran' => 'required|numeric|min:0.0001',
            'catatan' => 'nullable|string',
        ]);

        // Check unique product + material, excluding current
        $exists = Recipe::where('product_id', $productId)
            ->where('material_id', $request->material_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['material_id' => 'Material ini sudah ada dalam resep produk ini.'])->withInput();
        }

        $recipe->update([
            'material_id' => $request->material_id,
            'jumlah_takaran' => $request->jumlah_takaran,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('products.recipes.index', $productId)->with('success', 'Resep berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $productId = $recipe->product_id;
        $recipe->delete();

        return redirect()->route('products.recipes.index', $productId)->with('success', 'Resep berhasil dihapus.');
    }
}
