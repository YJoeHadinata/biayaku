<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionBatch>
 */
class ProductionBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'jumlah_output' => $this->faker->randomFloat(2, 1, 100),
            'tanggal_produksi' => $this->faker->date(),
            'biaya_tambahan' => $this->faker->optional()->numberBetween(0, 100000),
            'keterangan' => $this->faker->optional()->sentence(),
        ];
    }
}
