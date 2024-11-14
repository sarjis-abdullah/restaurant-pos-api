<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breakfast = Category::create([
            'name' => 'Breakfast'
        ]);
        $dinner = Category::create([
            'name' => 'Dinner'
        ]);
        $beverage = Category::create([
            'name' => 'Beverages'
        ]);

        $menu1 = Menu::create([
            'name' => 'Breakfast Specials',
            'status' => 'available',
            'branch_id' => 1, // Assume branch with ID 1 exists
        ]);

        $menu2 = Menu::create([
            'name' => 'Dinner Specials',
            'status' => 'unavailable', // Assume you may have statuses like 'available' and 'unavailable'
            'branch_id' => 1,
        ]);

        MenuItem::create([
            'name' => 'Scrambled Eggs',
            'price' => 3.50,
            'quantity' => 1,
            'category_id' => $breakfast->id, // Assume category 'Breakfast' has ID 1
            'discount_id' => null, // No discount applied
            'menu_id' => $menu1->id, // Links to 'Breakfast Specials' menu
            'type' => 'piece',
            'description' => 'Freshly scrambled eggs with a dash of pepper and salt.',
            'ingredients' => 'Eggs, Salt, Pepper, Butter',
            'preparation_time' => 10,
            'serves' => 1,
            'allow_other_discount' => true,
        ]);

        $discount = Discount::create([
            'type' => 'flat',
            'amount' => '10',
        ]);
        MenuItem::create([
            'name' => 'Pancake Platter',
            'price' => 10.00,
            'quantity' => 1,
            'category_id' => $breakfast->id,
            'discount_id' => $discount->id, // Assuming discount for a special promotion
            'menu_id' => $menu1->id,
            'type' => 'set_menu',
            'description' => 'Includes 3 pancakes, 2 scrambled eggs, and a serving of hash browns.',
            'ingredients' => 'Pancakes, Eggs, Potatoes, Butter, Syrup',
            'preparation_time' => 15,
            'serves' => 1,
            'allow_other_discount' => false,
        ]);

        MenuItem::create([
            'name' => 'Steak Dinner',
            'price' => 25.00,
            'quantity' => 1,
            'category_id' => $dinner->id, // Assume 'Dinner' category
            'discount_id' => 1, // Possible discount for high-value items
            'menu_id' => $menu2->id, // Links to 'Dinner Specials' menu
            'type' => 'mix_of_platter',
            'description' => 'Grilled steak served with a side of mashed potatoes and steamed vegetables.',
            'ingredients' => 'Steak, Potatoes, Broccoli, Carrots, Salt, Pepper',
            'preparation_time' => 25,
            'serves' => 1,
            'allow_other_discount' => true,
        ]);

        MenuItem::create([
            'name' => 'Fresh Orange Juice',
            'price' => 2.00,
            'quantity' => 1,
            'category_id' => $beverage->id, // Assume 'Beverages' category
            'discount_id' => null,
            'menu_id' => $menu1->id, // Could appear in Breakfast Specials as well
            'type' => 'piece',
            'description' => '100% freshly squeezed orange juice.',
            'ingredients' => 'Oranges',
            'preparation_time' => 5,
            'serves' => 1,
            'allow_other_discount' => true,
        ]);


    }
}
