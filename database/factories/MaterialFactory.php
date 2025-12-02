<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->word(),
            'unit' => $this->faker->randomElement(['kg', 'liter', 'pcs', 'gram']),
            'harga_satuan' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
