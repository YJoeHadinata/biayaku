<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductionBatch;

class HPPCalculationService
{
    /**
     * Calculate material cost considering unit conversion
     */
    public function calculateMaterialCost($recipe): float
    {
        $material = $recipe->material;
        $jumlahTakaran = $recipe->jumlah_takaran;

        // If material has qty_per_unit conversion, use it
        if ($material->qty_per_unit && $material->qty_per_unit > 0) {
            // Convert jumlah_takaran to the pricing unit
            // Example: if material is priced per kg (1000g) but recipe uses grams,
            // then actual amount in kg = jumlah_takaran / qty_per_unit
            $convertedAmount = $jumlahTakaran / $material->qty_per_unit;
            return $convertedAmount * $material->harga_satuan;
        }

        // Default: assume jumlah_takaran is already in the pricing unit
        return $jumlahTakaran * $material->harga_satuan;
    }

    /**
     * Calculate HPP for a production batch
     */
    public function calculateHPP(ProductionBatch $batch): array
    {
        $product = $batch->product;
        $recipes = $product->recipes()->with('material')->get();

        $hppTotal = 0;
        $breakdown = [];

        foreach ($recipes as $recipe) {
            // Calculate material cost considering unit conversion
            $materialCost = $this->calculateMaterialCost($recipe);
            $hppTotal += $materialCost;

            $breakdown[] = [
                'material_nama' => $recipe->material->nama,
                'material_unit' => $recipe->material->unit,
                'takaran' => $recipe->jumlah_takaran,
                'harga_satuan' => $recipe->material->harga_satuan,
                'total' => $materialCost,
            ];
        }

        $hppPerUnit = $batch->jumlah_output > 0 ? $hppTotal / $batch->jumlah_output : 0;
        $totalHppDenganTambahan = $hppTotal + $batch->biaya_tambahan;
        $totalHppPerUnitFinal = $batch->jumlah_output > 0 ? $totalHppDenganTambahan / $batch->jumlah_output : 0;

        return [
            'hpp_total' => $hppTotal,
            'hpp_per_unit' => $hppPerUnit,
            'total_hpp_dengan_tambahan' => $totalHppDenganTambahan,
            'total_hpp_per_unit_final' => $totalHppPerUnitFinal,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate HPP preview for a product (without creating batch)
     */
    public function calculateHPPPreview(Product $product, float $jumlahOutput = 1): array
    {
        $recipes = $product->recipes()->with('material')->get();

        $hppTotal = 0;
        $breakdown = [];

        foreach ($recipes as $recipe) {
            // Calculate material cost considering unit conversion
            $materialCost = $this->calculateMaterialCost($recipe);
            $hppTotal += $materialCost;

            $breakdown[] = [
                'material_nama' => $recipe->material->nama,
                'material_unit' => $recipe->material->unit,
                'takaran' => $recipe->jumlah_takaran,
                'harga_satuan' => $recipe->material->harga_satuan,
                'total' => $materialCost,
            ];
        }

        $hppPerUnit = $jumlahOutput > 0 ? $hppTotal / $jumlahOutput : 0;

        return [
            'hpp_total' => $hppTotal,
            'hpp_per_unit' => $hppPerUnit,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Update batch HPP calculations
     */
    public function updateBatchHPP(ProductionBatch $batch): void
    {
        $calculations = $this->calculateHPP($batch);

        $batch->update([
            'hpp_total' => $calculations['hpp_total'],
            'hpp_per_unit' => $calculations['hpp_per_unit'],
            'total_hpp_dengan_tambahan' => $calculations['total_hpp_dengan_tambahan'],
            'total_hpp_per_unit_final' => $calculations['total_hpp_per_unit_final'],
        ]);
    }
}
