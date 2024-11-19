<?php

namespace Database\Seeders;

use App\Enums\MenuStatus;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Tax;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
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
            'name' => 'Breakfast',
            'branch_id' => 1,
        ]);
        $dinner = Category::create([
            'name' => 'Dinner',
            'branch_id' => 1,
        ]);
        $beverage = Category::create([
            'name' => 'Beverages',
            'branch_id' => 1,
        ]);

        $menu1 = Menu::create([
            'name' => 'Breakfast Specials',
            'status' => 'available',
            'branch_id' => 1, // Assume branch with ID 1 exists
        ]);

        $menu2 = Menu::create([
            'name' => 'Dinner Specials',
            'status' => MenuStatus::unavailable->value, // Assume you may have statuses like 'available' and 'unavailable'
            'branch_id' => 1,
        ]);

        $taxIds = Tax::query()->pluck('id');


        $menuItem1 = MenuItem::create([
            'name' => 'Scrambled Eggs',
            'price' => 3.50,
            'quantity' => 1,
            'category_id' => $breakfast->id, // Assume category 'Breakfast' has ID 1
            'discount_id' => null, // No discount applied
            'tax_id' => null, // No discount applied
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
            "branch_id" => 1,
        ]);

        $menuItem2 = MenuItem::create([
            'name' => 'Pancake Platter',
            'price' => 10.00,
            'quantity' => 1,
            'category_id' => $breakfast->id,
            'discount_id' => $discount->id, // Assuming discount for a special promotion
            'tax_id' => $taxIds->random(),
            'menu_id' => $menu1->id,
            'type' => 'set_menu',
            'description' => 'Includes 3 pancakes, 2 scrambled eggs, and a serving of hash browns.',
            'ingredients' => 'Pancakes, Eggs, Potatoes, Butter, Syrup',
            'preparation_time' => 15,
            'serves' => 1,
            'allow_other_discount' => false,
        ]);

        $menuItem3 = MenuItem::create([
            'name' => 'Steak Dinner',
            'price' => 25.00,
            'quantity' => 1,
            'category_id' => $dinner->id, // Assume 'Dinner' category
            'discount_id' => 1, // Possible discount for high-value items
            'tax_id' => $taxIds->random(),
            'menu_id' => $menu2->id, // Links to 'Dinner Specials' menu
            'type' => 'mix_of_platter',
            'description' => 'Grilled steak served with a side of mashed potatoes and steamed vegetables.',
            'ingredients' => 'Steak, Potatoes, Broccoli, Carrots, Salt, Pepper',
            'preparation_time' => 25,
            'serves' => 1,
            'allow_other_discount' => true,
        ]);

        $menuItem4 = MenuItem::create([
            'name' => 'Fresh Orange Juice',
            'price' => 2.00,
            'quantity' => 1,
            'category_id' => $beverage->id, // Assume 'Beverages' category
            'discount_id' => null,
            'tax_id' => $taxIds->random(),
            'menu_id' => $menu1->id, // Could appear in Breakfast Specials as well
            'type' => 'piece',
            'description' => '100% freshly squeezed orange juice.',
            'ingredients' => 'Oranges',
            'preparation_time' => 5,
            'serves' => 1,
            'allow_other_discount' => true,
        ]);
        $faker = Faker::create();
        foreach (range(1,10) as $in){
            MenuItem::create([
                'name' => 'Fresh '.$faker->name,
                'price' => $faker->numberBetween(1,100),
                'quantity' => $faker->numberBetween(1,5),
                'category_id' => $beverage->id, // Assume 'Beverages' category
                'discount_id' => null,
                'tax_id' => $taxIds->random(),
                'menu_id' => $menu1->id, // Could appear in Breakfast Specials as well
                'type' => 'piece',
                'description' => '100% freshly '. $faker->name,
                'ingredients' => 'Oranges',
                'preparation_time' => 5,
                'serves' => 1,
                'allow_other_discount' => true,
            ]);
        }


        //orders

        $admin = User::find(2);
        $customer = User::find(4);
        $chef = User::find(6);
        $waiter = User::find(5);

        $tableIds = Table::query()->pluck('id');


        $order1 = Order::create([
            'order_by' => $customer->id,
            'prepare_by' => $chef->id,
            'created_by' => $admin->id,
            'table_id' => $tableIds->random(),
            'status' => OrderStatus::requested->value,
            'type' => 'dine_in',
            'branch_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $menuItem1->id, // Rui Fish Fry
            'total_price' => 400, // 200 x 2
            'quantity' => 2,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $menuItem2->id, // Shrimp Cocktail
            'total_price' => $menuItem4->price*rand(1,4),
            'quantity' => 1,
        ]);

        // Sample Order 2
        $order2 = Order::create([
            'order_by' => null,
            'prepare_by' => $chef->id,
            'created_by' => $admin->id,
            'table_id' => $tableIds->random(),
            'status' => OrderStatus::requested->value,
            'type' => 'take_away',
            'pickup_date' => Carbon::now()->addDay(),
            'branch_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'menu_item_id' => $menuItem3->id, // Grilled Salmon
            'total_price' => $menuItem3->price*rand(1,4),
            'quantity' => 1,
        ]);


        /// more
        ///
        $order3 = Order::create([
            'order_by' => $customer->id,
            'prepare_by' => $chef->id,
            'created_by' => $waiter->id,
            'table_id' => $tableIds->random(),
            'status' => OrderStatus::requested->value, // Order is being prepared
            'type' => 'dine_in',
            'branch_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'menu_item_id' => $menuItem4->id, // Rui Fish Fry
            'total_price' => $menuItem2->price*rand(1,4),
            'quantity' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'menu_item_id' => $menuItem3->id, // Grilled Salmon
            'total_price' => $menuItem2->price*rand(1,4),
            'quantity' => 1,
        ]);

        // Sample Order 4 (Take-away order with scheduled pickup)
        $order4 = Order::create([
            'order_by' => $customer->id,
            'prepare_by' => $chef->id,
            'created_by' => $admin->id,
            'table_id' => null,
            'status' => OrderStatus::requested->value,
            'type' => 'take_away',
            'pickup_date' => Carbon::now()->addHours(2),
            'branch_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItem1->id, // Shrimp Cocktail
            'total_price' => $menuItem2->price*rand(1,4),
            'quantity' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItem1->id, // Rui Fish Fry (additional item)
            'total_price' => $menuItem3->price*rand(1,4),
            'quantity' => 1,
        ]);

        // Sample Order 5 (Dine-in order with multiple quantities)
        $order5 = Order::create([
            'order_by' => $customer->id,
            'prepare_by' => $chef->id,
            'created_by' => $waiter->id,
            'table_id' => $tableIds->random(),
            'status' => OrderStatus::requested->value,
            'type' => 'dine_in',
            'branch_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItem4->id, // Grilled Salmon
            'total_price' => $menuItem1->price*rand(1,4), // 350 x 2
            'quantity' => 2,
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItem4->id, // Shrimp Cocktail
            'total_price' => $menuItem2->price*rand(1,4), // 150 x 2
            'quantity' => 2,
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItem4->id, // Rui Fish Fry
            'total_price' => $menuItem4->price*rand(1,4), // 200 x 2
            'quantity' => 2,
        ]);

        // Add sample order status messages
        dump("Order 3 (Dine-in) is in 'processing' status.");
        dump("Order 4 (Take-away) is 'ready for pickup' with scheduled time.");
        dump("Order 5 (Dine-in) is 'completed' with multiple quantities of items.");


    }
}
