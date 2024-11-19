<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variations = [
            // Size Variations
            ['name' => 'Small', 'type' => 'size', 'price_modifier' => -1.00, 'menu_item_id' => 1],
            ['name' => 'Medium', 'type' => 'size', 'price_modifier' => 0.00, 'menu_item_id' => 1],
            ['name' => 'Large', 'type' => 'size', 'price_modifier' => 2.00, 'menu_item_id' => 1],

            // Customization Variations
            ['name' => 'Mild', 'type' => 'customization', 'price_modifier' => 0.00, 'menu_item_id' => 2],
            ['name' => 'Medium Spicy', 'type' => 'customization', 'price_modifier' => 0.00, 'menu_item_id' => 2],
            ['name' => 'Extra Hot', 'type' => 'customization', 'price_modifier' => 1.00, 'menu_item_id' => 2],

            // Add-ons
            ['name' => 'Extra Cheese', 'type' => 'add-on', 'price_modifier' => 1.50, 'menu_item_id' => 3],
            ['name' => 'Mushrooms', 'type' => 'add-on', 'price_modifier' => 0.75, 'menu_item_id' => 3],
            ['name' => 'Olives', 'type' => 'add-on', 'price_modifier' => 0.75, 'menu_item_id' => 3],

            // Preparation Style
            ['name' => 'Grilled', 'type' => 'preparation', 'price_modifier' => 0.50, 'menu_item_id' => 4],
            ['name' => 'Baked', 'type' => 'preparation', 'price_modifier' => 0.00, 'menu_item_id' => 4],
            ['name' => 'Steamed', 'type' => 'preparation', 'price_modifier' => -0.50, 'menu_item_id' => 4],

            // Ingredients
            ['name' => 'Almond Milk', 'type' => 'ingredient', 'price_modifier' => 1.50, 'menu_item_id' => 5],
            ['name' => 'Skim Milk', 'type' => 'ingredient', 'price_modifier' => 0.00, 'menu_item_id' => 5],
            ['name' => 'Oat Milk', 'type' => 'ingredient', 'price_modifier' => 1.00, 'menu_item_id' => 5],

            // Serving Options
            ['name' => 'Dine-In', 'type' => 'serving', 'price_modifier' => 0.00, 'menu_item_id' => 6],
            ['name' => 'Takeaway', 'type' => 'serving', 'price_modifier' => -0.50, 'menu_item_id' => 6],
            ['name' => 'Delivery', 'type' => 'serving', 'price_modifier' => 2.00, 'menu_item_id' => 6],

            // Material or Container
            ['name' => 'Glass Cup', 'type' => 'container', 'price_modifier' => 0.50, 'menu_item_id' => 7],
            ['name' => 'Plastic Cup', 'type' => 'container', 'price_modifier' => -0.25, 'menu_item_id' => 7],
            ['name' => 'Paper Cup', 'type' => 'container', 'price_modifier' => 0.00, 'menu_item_id' => 7],

            // Region-Specific Variations
            ['name' => 'Thin Crust', 'type' => 'regional', 'price_modifier' => 0.00, 'menu_item_id' => 8],
            ['name' => 'Stuffed Crust', 'type' => 'regional', 'price_modifier' => 3.00, 'menu_item_id' => 8],

            // Diet Preferences
            ['name' => 'Vegan', 'type' => 'diet', 'price_modifier' => 2.00, 'menu_item_id' => 9],
            ['name' => 'Gluten-Free', 'type' => 'diet', 'price_modifier' => 1.50, 'menu_item_id' => 9],
        ];

        // Insert all variations
        DB::table('variations')->insert($variations);

        $variations = [
            ['name' => 'Red', 'type' => 'color', 'price_modifier' => 0.50, 'menu_item_id' => 10],
            ['name' => 'Green', 'type' => 'color', 'price_modifier' => 0.75, 'menu_item_id' => 10],
            ['name' => 'Yellow', 'type' => 'color', 'price_modifier' => 1.00, 'menu_item_id' => 10],
            ['name' => 'Blue', 'type' => 'color', 'price_modifier' => 0.00, 'menu_item_id' => 11],
            ['name' => 'Black', 'type' => 'color', 'price_modifier' => 1.25, 'menu_item_id' => 12],
        ];

        DB::table('variations')->insert($variations);
    }
}
