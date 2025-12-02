<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the default branch
        $branch = Branch::where('name', 'Kantor Pusat')->first();

        if (!$branch) {
            $this->command->error('Default branch not found. Please run BranchSeeder first.');
            return;
        }

        // Create sample materials
        $materials = [
            [
                'nama' => 'Biji Kopi Arabika',
                'unit' => 'kg',
                'harga_satuan' => 150000,
                'base_unit' => 'gram',
                'qty_per_unit' => 1000,
                'deskripsi' => 'Biji kopi arabika premium grade A',
            ],
            [
                'nama' => 'Gula Pasir',
                'unit' => 'kg',
                'harga_satuan' => 15000,
                'base_unit' => 'gram',
                'qty_per_unit' => 1000,
                'deskripsi' => 'Gula pasir putih untuk bahan campuran',
            ],
            [
                'nama' => 'Garam Dapur',
                'unit' => 'kg',
                'harga_satuan' => 8000,
                'base_unit' => 'gram',
                'qty_per_unit' => 1000,
                'deskripsi' => 'Garam dapur halus',
            ],
            [
                'nama' => 'Kemasan Plastik',
                'unit' => 'pcs',
                'harga_satuan' => 500,
                'deskripsi' => 'Kemasan plastik 1kg kopi instan',
            ],
            [
                'nama' => 'Label Sticker',
                'unit' => 'pcs',
                'harga_satuan' => 200,
                'deskripsi' => 'Label sticker branding produk',
            ],
        ];

        foreach ($materials as $materialData) {
            Material::create(array_merge($materialData, ['branch_id' => $branch->id]));
        }

        $this->command->info('Created ' . count($materials) . ' sample materials');

        // Create sample products
        $products = [
            [
                'nama' => 'Kopi Instan Premium 1kg',
                'unit_output' => 'kg',
                'deskripsi' => 'Kopi instan premium siap seduh',
            ],
            [
                'nama' => 'Kopi Instan Ekonomis 500g',
                'unit_output' => 'kg',
                'deskripsi' => 'Kopi instan ekonomis untuk segmen menengah',
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $product = Product::create(array_merge($productData, ['branch_id' => $branch->id]));
            $createdProducts[] = $product;
        }

        $this->command->info('Created ' . count($products) . ' sample products');

        // Create recipes for products
        $kopiArabika = Material::where('nama', 'Biji Kopi Arabika')->where('branch_id', $branch->id)->first();
        $gula = Material::where('nama', 'Gula Pasir')->where('branch_id', $branch->id)->first();
        $garam = Material::where('nama', 'Garam Dapur')->where('branch_id', $branch->id)->first();

        $recipes = [
            // Kopi Instan Premium 1kg
            [
                'product_id' => $createdProducts[0]->id,
                'material_id' => $kopiArabika->id,
                'jumlah_takaran' => 1.2, // 1.2 kg kopi per 1 kg output
                'catatan' => 'Takaran utama untuk rasa premium',
            ],
            [
                'product_id' => $createdProducts[0]->id,
                'material_id' => $gula->id,
                'jumlah_takaran' => 0.2, // 0.2 kg gula per 1 kg output
                'catatan' => 'Pemanis alami',
            ],
            [
                'product_id' => $createdProducts[0]->id,
                'material_id' => $garam->id,
                'jumlah_takaran' => 0.01, // 0.01 kg garam per 1 kg output
                'catatan' => 'Penguat rasa',
            ],
            // Kopi Instan Ekonomis 500g
            [
                'product_id' => $createdProducts[1]->id,
                'material_id' => $kopiArabika->id,
                'jumlah_takaran' => 0.8, // 0.8 kg kopi per 0.5 kg output (1.6 kg per kg)
                'catatan' => 'Takaran lebih tinggi untuk kompensasi kualitas',
            ],
            [
                'product_id' => $createdProducts[1]->id,
                'material_id' => $gula->id,
                'jumlah_takaran' => 0.15, // 0.15 kg gula per 0.5 kg output (0.3 kg per kg)
                'catatan' => 'Pemanis ekonomis',
            ],
        ];

        foreach ($recipes as $recipeData) {
            Recipe::create(array_merge($recipeData, ['branch_id' => $branch->id]));
        }

        $this->command->info('Created ' . count($recipes) . ' sample recipes');

        // Create sample production batches
        $batches = [
            [
                'product_id' => $createdProducts[0]->id,
                'jumlah_output' => 10, // 10 kg
                'tanggal_produksi' => now()->subDays(5),
                'biaya_tambahan' => 50000, // Biaya packing, upah, dll
                'harga_jual' => 180000, // Harga jual per kg
                'keterangan' => 'Batch pertama kopi premium',
            ],
            [
                'product_id' => $createdProducts[0]->id,
                'jumlah_output' => 15, // 15 kg
                'tanggal_produksi' => now()->subDays(2),
                'biaya_tambahan' => 75000,
                'harga_jual' => 175000,
                'keterangan' => 'Batch kedua dengan harga promo',
            ],
            [
                'product_id' => $createdProducts[1]->id,
                'jumlah_output' => 25, // 25 kg (50 pack @ 500g)
                'tanggal_produksi' => now()->subDays(3),
                'biaya_tambahan' => 40000,
                'harga_jual' => 120000, // Harga jual per kg
                'keterangan' => 'Batch ekonomis untuk pasar massal',
            ],
        ];

        foreach ($batches as $batchData) {
            $batch = ProductionBatch::create(array_merge($batchData, ['branch_id' => $branch->id]));

            // Calculate HPP for the batch
            $hppService = app(\App\Services\HPPCalculationService::class);
            $hppService->updateBatchHPP($batch);
        }

        $this->command->info('Created ' . count($batches) . ' sample production batches with HPP calculations');

        $this->command->info('✅ Demo data seeding completed successfully!');
        $this->command->info('📊 You can now login and explore the application with sample data.');
    }
}
