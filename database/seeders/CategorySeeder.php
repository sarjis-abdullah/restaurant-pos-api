<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Starters',
            'Main Course',
            'Beverages',
            'Desserts',
            'Salads',
'Sandwiches',
'Pasta',
'Pizza',
'Burgers',
'Wraps',
'Grilled Items',
'Vegetarian',
'Seafood',
'Sushi',
'Snacks',
'Smoothies',
'Appetizers',
'Kids Menu'];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
