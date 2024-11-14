<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Floor;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
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

        $table = Table::create([
            "name" => "Table ".$faker->name,
            "branch_id" => $branch->id,
            "floor_id" => $floor->id,
        ]);

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
            'table_id' => $table->id,
            'order_by' => $customer->id,
            'status' => OrderStatus::requested->value,
            'branch_id' => $branch->id,
        ];
        $orderData['taken_by'] = $admin->id;
        $this->processOrder($orderData, $admin, $chef, $menuItem);

        $orderData['order_by'] = null;
        $orderData['taken_by'] = $waiter->id;
        $this->processOrder($orderData, $admin, $chef, $menuItem);

        DB::commit();
    }

    function processOrder($data, $admin, $chef, $menuItem)
    {
        $order = Order::create($data);
        $orderQty = rand(1,4);
        OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $menuItem->id,
            'total_price' => $menuItem->price*$orderQty,
            'quantity' => $orderQty,
        ]);

        dump('order placed');

        $updateData = [
            'status' => OrderStatus::received->value
        ];
        $updateData['received_by'] = $order->taken_by;
//        if (isset($order?->taken_by)){
//            $updateData['received_by'] = $order->taken_by;
//            dump('order received_by ');
//        }else{
//            $updateData['received_by'] = $admin->id;
//        }
        $order->update($updateData);

        dump('order received');

        $order->update([
            'status' => OrderStatus::ready_for_kitchen->value
        ]);

        dump('order ready_for_kitchen');

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
