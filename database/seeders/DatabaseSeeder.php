<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Floor;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Tax;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            PaymentSeeder::class,
        ]);

        return;
        DB::beginTransaction();
        $faker = Faker::create();

        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        $admin = User::find(2);
        $customer = User::find(4);
        $chef = User::find(6);
        $waiter = User::find(5);

        $company = Company::create([
            'name' => 'Company '.$faker->company,
        ]);

        $branch = Branch::create([
            'name' => 'Branch '.$faker->name,
            'company_id' => $company->id
        ]);

        $floor = Floor::create([
            "name" => "Floor ".$faker->firstName(),
            "branch_id" => $branch->id,
        ]);

        foreach (range(1, 10) as $index) {
            $table = Table::create([
                "name" => "Table ".$faker->name,
                "branch_id" => $branch->id,
                "floor_id" => $floor->id,
            ]);
        }

        foreach (range(1, 3) as $index) {
            Tax::create([
                'name' => 'Tax '.$faker->name,
                "rate" => 5,
                "branch_id" => $branch->id,
                "type" => 'percentage',
            ]);
        }

        $this->call([
            MenuSeeder::class,
        ]);

        $menu = Menu::create([
            'name' => $faker->company,
            "branch_id" => $branch->id,
        ]);

        $menuItem = MenuItem::create([
            "name" => $faker->company,
            "price" => 200,
            "quantity" => 2,
            "type" => 'set-menu',
            "preparation_time" => '20',
            "serves" => 2,
            "menu_id" => $menu->id,
        ]);

        /*

        $toDate = \Carbon\Carbon::parse('2025-11-01 15:04:19');

        $table->update([
            'status' => TableStatus::requestToBook->value,
            'request_by' => $customer->id,
            'booking_from' => Carbon::now()->toDateTimeString(),
            'booking_to' => $toDate,
        ]);

        dump('Table is requestToBook');

        $table->update([
            'status' => TableStatus::booked->value,
            'received_by' => $admin->id,
        ]);

        dump('Table is booked and received_by admin');

        */
        $orderData = [
            'table_id'  => $table->id,
            'created_by' => $waiter->id,
            'status'    => OrderStatus::requested->value,
            'branch_id' => $branch->id,
            'type'      => OrderType::dine_in->value,
        ];



//        $this->processOrder($orderData, $chef, $menuItem);

        DB::commit();
    }

    function processOrder($data, $chef, $menuItem)
    {
        $order = Order::create($data);

        $orderQty = rand(1,4);
        OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $menuItem->id,
            'total_price' => $menuItem->price*rand(1,4),
            'quantity' => $orderQty,
        ]);

        dump('order placed');

        $order->update([
            'status' => OrderStatus::received->value
        ]);

        dump('order received');

        $order->update([
            'prepare_by' => $chef->id,
            'status' => OrderStatus::processing->value
        ]);

        dump('order assigned to chef and order is processing');

        $order->update([
            'status' => OrderStatus::ready->value
        ]);

        dump('order is ready to be served');
    }
}
