<?php

namespace Database\Seeders;

use App\Models\Addon;
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
        $addons = [
            ['name' => 'Extra Cheese', 'price' => 1.50, 'menu_item_id' => 1],
            ['name' => 'BBQ Sauce', 'price' => 0.75, 'menu_item_id' => 1],
            ['name' => 'Bacon', 'price' => 2.00, 'menu_item_id' => 2],
            ['name' => 'Avocado', 'price' => 1.75, 'menu_item_id' => 2],
            ['name' => 'Garlic Dip', 'price' => 0.50, 'menu_item_id' => 3],
            ['name' => 'Chili Flakes', 'price' => 0.25, 'menu_item_id' => 3],
            ['name' => 'Chili '.$faker->name, 'price' => rand(1, 100), 'menu_item_id' => rand(1,15)],
        ];

        foreach (range(1, 10) as $index) {
            Addon::create(['name' => 'Chili '.$faker->name, 'price' => rand(20, 30), 'menu_item_id' => rand(1,15)]);
        }

        DB::table('addons')->insert($addons);
    }
}
