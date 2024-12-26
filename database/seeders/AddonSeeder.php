<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\AddonVariant;
use App\Models\MenuItem;
use App\Models\Recipe;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $menuItemIds = MenuItem::query()->pluck('id');
        $recipeIds = Recipe::query()->pluck('id');

        $addons = [
            ['name' => 'Extra Cheese', 'price' => 1.50, 'menu_item_id' => $menuItemIds->random()],
            ['name' => 'BBQ Sauce', 'price' => 0.75, 'menu_item_id' => $menuItemIds->random()],
            ['name' => 'Bacon', 'price' => 2.00, 'menu_item_id' => $menuItemIds->random()],
            ['name' => 'Avocado', 'price' => 1.75, 'menu_item_id' => $menuItemIds->random()],
            ['name' => 'Garlic Dip', 'price' => 0.50, 'menu_item_id' => $menuItemIds->random()],
            ['name' => 'Chili Flakes', 'price' => 0.25, 'menu_item_id' =>$menuItemIds->random()],
        ];
dump(1);
        foreach (range(1, 10) as $index) {
            Addon::create([
                'name' => 'Addon Chili '.$faker->name,
                'price' => rand(20, 30),
                'menu_item_id' =>$menuItemIds->random(),
                "recipe_id" => $recipeIds->random(),
            ]);
        }
        dump(11);

        $addons = array_map(function ($item) use ($recipeIds) {
            $item['recipe_id'] = $recipeIds->random();
            return $item;
        }, $addons);

        DB::table('addons')->insert($addons);

        $cheese = Addon::create([
            'name' => 'Cheese',
            'price' => 10.00,
            'has_variants' => true,
            'menu_item_id' => $menuItemIds->random(),
            "recipe_id" => $recipeIds->random(),
        ]);
        $sauce = Addon::create([
            'name' => 'Sauce',
            'price' => 5.00,
            'has_variants' => true,
            'menu_item_id' => $menuItemIds->random(),
            "recipe_id" => $recipeIds->random(),
        ]);

        $addonvariants = [
            ['addon_id' => $cheese->id, 'type' => 'size', 'name' => 'small', 'price' => -2.00],
            ['addon_id' => $cheese->id, 'type' => 'size', 'name' => 'large', 'price' => 3.00],
            ['addon_id' => $sauce->id, 'type' => 'color', 'name' => 'red', 'price' => 0.00],
            ['addon_id' => $sauce->id, 'type' => 'color', 'name' => 'green', 'price' => 1.00],
        ];
        $addonvariants = array_map(function ($item) use ($recipeIds) {
            $item['recipe_id'] = $recipeIds->random();
            return $item;
        }, $addonvariants);

        AddonVariant::insert($addonvariants);
        dump(2);
        $addonvariants = [
            // Cheese Addon
            ['type' => 'size', 'name' => 'Small', 'price' => -2.00],
            ['type' => 'size', 'name' => 'Medium', 'price' => 0.00],
            ['type' => 'size', 'name' => 'Large', 'price' => 3.00],

            // Sauce Addon
            ['type' => 'color', 'name' => 'Red', 'price' => 0.00],
            ['type' => 'color', 'name' => 'Green', 'price' => 0.50],
            ['type' => 'color', 'name' => 'Blue', 'price' => 1.00],

            // Toppings Addon
            ['type' => 'type', 'name' => 'Olives', 'price' => 1.50],
            ['type' => 'type', 'name' => 'Mushrooms', 'price' => 2.00],
            ['type' => 'type', 'name' => 'Jalapenos', 'price' => 1.75],

            // Fries Addon
            ['type' => 'flavor', 'name' => 'Regular', 'price' => 0.00],
            ['type' => 'flavor', 'name' => 'Spicy', 'price' => 0.50],
            ['type' => 'flavor', 'name' => 'Cheese Loaded', 'price' => 2.00],

            // Drinks Addon
            ['type' => 'size', 'name' => 'Small', 'price' => -1.00],
            ['type' => 'size', 'name' => 'Medium', 'price' => 0.00],
            ['type' => 'size', 'name' => 'Large', 'price' => 1.50],
            ['type' => 'size', 'name' => 'Extra Large', 'price' => 2.50],
        ];

        // Fetch all Addon IDs
        $addonIds = Addon::pluck('id')->toArray();
//        $addonvariants = array_map(function ($item) use ($recipeIds) {
//            $item['recipe_id'] = $recipeIds->random();
//            return $item;
//        }, $addonvariants);
        dump(66);
        foreach ($addonvariants as $variation) {
            AddonVariant::create(array_merge($variation, [
                "recipe_id" => $recipeIds->random(),
                'addon_id' => $addonIds[array_rand($addonIds)], // Assign random addon_id
            ]));
        }
        dump('dfdfdf');
    }
}
