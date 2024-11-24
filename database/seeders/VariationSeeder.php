<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class variantseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = [
            // Size variants
            ['name' => 'Small', 'type' => 'size', 'price' => -1.00, 'menu_item_id' => 1],
            ['name' => 'Medium', 'type' => 'size', 'price' => 0.00, 'menu_item_id' => 1],
            ['name' => 'Large', 'type' => 'size', 'price' => 2.00, 'menu_item_id' => 1],

            // Customization variants
            ['name' => 'Mild', 'type' => 'customization', 'price' => 0.00, 'menu_item_id' => 2],
            ['name' => 'Medium Spicy', 'type' => 'customization', 'price' => 0.00, 'menu_item_id' => 2],
            ['name' => 'Extra Hot', 'type' => 'customization', 'price' => 1.00, 'menu_item_id' => 2],

            // Add-ons
            ['name' => 'Extra Cheese', 'type' => 'add-on', 'price' => 1.50, 'menu_item_id' => 3],
            ['name' => 'Mushrooms', 'type' => 'add-on', 'price' => 0.75, 'menu_item_id' => 3],
            ['name' => 'Olives', 'type' => 'add-on', 'price' => 0.75, 'menu_item_id' => 3],

            // Preparation Style
            ['name' => 'Grilled', 'type' => 'preparation', 'price' => 0.50, 'menu_item_id' => 4],
            ['name' => 'Baked', 'type' => 'preparation', 'price' => 0.00, 'menu_item_id' => 4],
            ['name' => 'Steamed', 'type' => 'preparation', 'price' => -0.50, 'menu_item_id' => 4],

            // Ingredients
            ['name' => 'Almond Milk', 'type' => 'ingredient', 'price' => 1.50, 'menu_item_id' => 5],
            ['name' => 'Skim Milk', 'type' => 'ingredient', 'price' => 0.00, 'menu_item_id' => 5],
            ['name' => 'Oat Milk', 'type' => 'ingredient', 'price' => 1.00, 'menu_item_id' => 5],

            // Serving Options
            ['name' => 'Dine-In', 'type' => 'serving', 'price' => 0.00, 'menu_item_id' => 6],
            ['name' => 'Takeaway', 'type' => 'serving', 'price' => -0.50, 'menu_item_id' => 6],
            ['name' => 'Delivery', 'type' => 'serving', 'price' => 2.00, 'menu_item_id' => 6],

            // Material or Container
            ['name' => 'Glass Cup', 'type' => 'container', 'price' => 0.50, 'menu_item_id' => 7],
            ['name' => 'Plastic Cup', 'type' => 'container', 'price' => -0.25, 'menu_item_id' => 7],
            ['name' => 'Paper Cup', 'type' => 'container', 'price' => 0.00, 'menu_item_id' => 7],

            // Region-Specific variants
            ['name' => 'Thin Crust', 'type' => 'regional', 'price' => 0.00, 'menu_item_id' => 8],
            ['name' => 'Stuffed Crust', 'type' => 'regional', 'price' => 3.00, 'menu_item_id' => 8],

            // Diet Preferences
            ['name' => 'Vegan', 'type' => 'diet', 'price' => 2.00, 'menu_item_id' => 9],
            ['name' => 'Gluten-Free', 'type' => 'diet', 'price' => 1.50, 'menu_item_id' => 9],
        ];

        // Insert all variants
        DB::table('variants')->insert($variants);

        $variants = [
            ['name' => 'Red', 'type' => 'color', 'price' => 0.50, 'menu_item_id' => 10],
            ['name' => 'Green', 'type' => 'color', 'price' => 0.75, 'menu_item_id' => 10],
            ['name' => 'Yellow', 'type' => 'color', 'price' => 1.00, 'menu_item_id' => 10],
            ['name' => 'Blue', 'type' => 'color', 'price' => 0.00, 'menu_item_id' => 11],
            ['name' => 'Black', 'type' => 'color', 'price' => 1.25, 'menu_item_id' => 12],
        ];

        DB::table('variants')->insert($variants);
    }
}
