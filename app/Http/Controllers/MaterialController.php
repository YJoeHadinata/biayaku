<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Material::where('branch_id', auth()->user()->getCurrentBranchId());

        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $materials = $query->paginate(10);

        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:materials,nama',
            'unit' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
            'qty_per_unit' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Check material limit for non-super-admin users
        if (!$user->isSuperAdmin()) {
            $currentMaterialCount = Material::where('branch_id', $user->getCurrentBranchId())->count();
            if ($user->hasExceededLimit('materials', $currentMaterialCount)) {
                return redirect()->route('materials.index')->with('error', 'Anda telah mencapai batas maksimal material (' . $user->getUsageLimit('materials') . ' material). Upgrade paket Anda untuk membuat lebih banyak material.');
            }
        }

        Material::create($request->only(['nama', 'unit', 'harga_satuan', 'base_unit', 'qty_per_unit', 'deskripsi']) + ['branch_id' => $user->getCurrentBranchId()]);

        return redirect()->route('materials.index')->with('success', 'Material berhasil ditambahkan.');
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
        $material = Material::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $material = Material::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:materials,nama,' . $id,
            'unit' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
            'qty_per_unit' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $material->update($request->only(['nama', 'unit', 'harga_satuan', 'base_unit', 'qty_per_unit', 'deskripsi']));

        return redirect()->route('materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::where('branch_id', auth()->user()->getCurrentBranchId())->findOrFail($id);
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Material berhasil dihapus.');
    }
}
