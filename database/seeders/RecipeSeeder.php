<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $burgerRecipe = Recipe::create([
                'name' => 'Classic Beef Burger',
                'description' => 'Classic Beef Burger description',
            ]);

            $products = Product::query()->inRandomOrder()->take(4)->get(['id', 'unit']); // Get up to 4 products

            $recipeIngredients = collect([]);

            foreach ($products as $product) {
                $recipeIngredients->push([
                    'product_id' => $product->id,
                    'recipe_id' => $burgerRecipe->id,
                    'quantity' => rand(1, 5), // Random quantity
                    'unit' => $product->unit, // Unit from product
                    'created_at' => now(), // Timestamp for batch insert
                    'updated_at' => now(),
                ]);
            }

            RecipeIngredient::insert($recipeIngredients->toArray());

            echo "Recipe created successfully with up to 4 ingredients.";

            DB::commit();
        } catch (\Exception $exception){
            echo $exception->getMessage();
            DB::rollBack();
        }
    }
}
