<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $units = ['kg', 'g', 'l', 'pcs', 'set', 'box', 'dozen', 'pack', 'roll'];
        return [
            'name' => 'Product '.fake()->name(),
            'description' => fake()->text(),
            'unit' => $units[array_rand($units)],
            'barcode' => uniqid(),
            'status' => 1,
        ];
    }
}
