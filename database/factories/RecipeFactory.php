<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
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
            'material_id' => Material::factory(),
            'jumlah_takaran' => $this->faker->randomFloat(4, 0.1, 5),
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }
}
