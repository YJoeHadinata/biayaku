<?php

use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Recipe;
use App\Models\User;
use App\Services\HPPCalculationService;

test('hpp calculation works correctly', function () {
    // Create materials
    $material1 = Material::factory()->create(['nama' => 'Biji Kopi', 'harga_satuan' => 100000, 'unit' => 'kg']);
    $material2 = Material::factory()->create(['nama' => 'Gula', 'harga_satuan' => 20000, 'unit' => 'kg']);

    // Create product
    $product = Product::factory()->create(['nama' => 'Kopi Instan', 'unit_output' => 'kg']);

    // Create recipes
    Recipe::factory()->create([
        'product_id' => $product->id,
        'material_id' => $material1->id,
        'jumlah_takaran' => 1.2, // 1.2 kg per kg output
    ]);

    Recipe::factory()->create([
        'product_id' => $product->id,
        'material_id' => $material2->id,
        'jumlah_takaran' => 0.2, // 0.2 kg per kg output
    ]);

    // Create production batch
    $batch = ProductionBatch::factory()->create([
        'product_id' => $product->id,
        'jumlah_output' => 10, // 10 kg output
        'biaya_tambahan' => 50000,
    ]);

    // Calculate HPP
    $hppService = new HPPCalculationService();
    $calculations = $hppService->calculateHPP($batch);

    // Expected calculations:
    // Material 1: 1.2 * 100000 = 120000
    // Material 2: 0.2 * 20000 = 4000
    // Total material cost: 124000
    // HPP per unit: 124000 / 10 = 12400
    // Total with additional: 124000 + 50000 = 174000
    // Final HPP per unit: 174000 / 10 = 17400

    expect($calculations['hpp_total'])->toBe(124000.0);
    expect($calculations['hpp_per_unit'])->toBe(12400.0);
    expect($calculations['total_hpp_dengan_tambahan'])->toBe(174000.0);
    expect($calculations['total_hpp_per_unit_final'])->toBe(17400.0);
    expect(count($calculations['breakdown']))->toBe(2);
});

test('production batch can be created with hpp calculation', function () {
    $user = User::factory()->create();

    // Create materials and product with recipes first
    $material = Material::factory()->create();
    $product = Product::factory()->create();
    Recipe::factory()->create([
        'product_id' => $product->id,
        'material_id' => $material->id,
        'jumlah_takaran' => 1.0,
    ]);

    $batchData = [
        'product_id' => $product->id,
        'jumlah_output' => 5,
        'tanggal_produksi' => now()->format('Y-m-d'),
        'biaya_tambahan' => 10000,
        'keterangan' => 'Test batch',
    ];

    $response = $this
        ->actingAs($user)
        ->post('/production-batches', $batchData);

    $response->assertRedirect();
    $this->assertDatabaseHas('production_batches', [
        'product_id' => $product->id,
        'jumlah_output' => 5,
        'biaya_tambahan' => 10000,
    ]);
});
