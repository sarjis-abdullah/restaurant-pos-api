<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\AddonVariation;
use App\Models\Menu;
use App\Models\MenuItem;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            Addon::create(['name' => 'Addon Chili '.$faker->name, 'price' => rand(20, 30), 'menu_item_id' =>$menuItemIds->random()]);
        }
        dump(11);

        DB::table('addons')->insert($addons);

        $cheese = Addon::create(['name' => 'Cheese', 'price' => 10.00, 'has_variants' => true,  'menu_item_id' => $menuItemIds->random()]);
        $sauce = Addon::create(['name' => 'Sauce', 'price' => 5.00, 'has_variants' => true, 'menu_item_id' => $menuItemIds->random()]);

        AddonVariation::insert([
            ['addon_id' => $cheese->id, 'attribute' => 'size', 'value' => 'small', 'price' => -2.00],
            ['addon_id' => $cheese->id, 'attribute' => 'size', 'value' => 'large', 'price' => 3.00],
            ['addon_id' => $sauce->id, 'attribute' => 'color', 'value' => 'red', 'price' => 0.00],
            ['addon_id' => $sauce->id, 'attribute' => 'color', 'value' => 'green', 'price' => 1.00],
        ]);
        dump(2);
        $addonvariants = [
            // Cheese Addon
            ['attribute' => 'size', 'value' => 'Small', 'price' => -2.00],
            ['attribute' => 'size', 'value' => 'Medium', 'price' => 0.00],
            ['attribute' => 'size', 'value' => 'Large', 'price' => 3.00],

            // Sauce Addon
            ['attribute' => 'color', 'value' => 'Red', 'price' => 0.00],
            ['attribute' => 'color', 'value' => 'Green', 'price' => 0.50],
            ['attribute' => 'color', 'value' => 'Blue', 'price' => 1.00],

            // Toppings Addon
            ['attribute' => 'type', 'value' => 'Olives', 'price' => 1.50],
            ['attribute' => 'type', 'value' => 'Mushrooms', 'price' => 2.00],
            ['attribute' => 'type', 'value' => 'Jalapenos', 'price' => 1.75],

            // Fries Addon
            ['attribute' => 'flavor', 'value' => 'Regular', 'price' => 0.00],
            ['attribute' => 'flavor', 'value' => 'Spicy', 'price' => 0.50],
            ['attribute' => 'flavor', 'value' => 'Cheese Loaded', 'price' => 2.00],

            // Drinks Addon
            ['attribute' => 'size', 'value' => 'Small', 'price' => -1.00],
            ['attribute' => 'size', 'value' => 'Medium', 'price' => 0.00],
            ['attribute' => 'size', 'value' => 'Large', 'price' => 1.50],
            ['attribute' => 'size', 'value' => 'Extra Large', 'price' => 2.50],
        ];

        // Fetch all Addon IDs
        $addonIds = Addon::pluck('id')->toArray();
        dump(66);
        foreach ($addonvariants as $variation) {
            AddonVariation::create(array_merge($variation, [
                'addon_id' => $addonIds[array_rand($addonIds)], // Assign random addon_id
            ]));
        }
        dump('dfdfdf');
    }
}
