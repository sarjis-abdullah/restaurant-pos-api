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
use App\Models\Table;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        $admin = User::find(2);
        $customer = User::find(4);
        $chef = User::find(6);
        $waiter = User::find(5);

        $company = Company::create([
            'name' => 'Company 1 seeder'
        ]);

        $branch = Branch::create([
            'name' => 'Branch 1 seeder',
            'company_id' => $company->id
        ]);

        $floor = Floor::create([
            "name" => "Floor 1",
            "branch_id" => $branch->id,
        ]);

        $table = Table::create([
            "name" => "Table 1",
            "branch_id" => $branch->id,
            "floor_id" => $floor->id,
            "max_seat" => 10
        ]);

        $toDate = \Carbon\Carbon::parse('2025-11-01 15:04:19');

        $table->update([
            'status' => TableStatus::requestToBook->value,
            'request_by' => $customer->id,
            'booking_from' => Carbon::now()->toDateTimeString(),
            'booking_to' => $toDate,
        ]);

        $table->update([
            'status' => TableStatus::booked->value,
            'received_by' => $admin->id,
        ]);

        $menu = Menu::create([
            'name' => 'Sea food',
            "branch_id" => $branch->id,
        ]);

        $menuItem = MenuItem::create([
            "name" => 'Rui fish fry',
            "price" => 200,
            "quantity" => 2,
            "type" => 'set-menu',
            "preparation_time" => '20',
            "serves" => 2,
            "menu_id" => $menu->id,
        ]);

        $orderData = [
            'table_id' => $table->id,
            'menu_item_id' => $menuItem->id,
            'order_by' => $customer->id,
            'status' => OrderStatus::requested->value,
            'branch_id' => $branch->id,
        ];
        $this->processOrder($orderData, $admin, $chef);

        $orderData['order_by'] = null;
        $orderData['taken_by'] = $waiter->id;
        $this->processOrder($orderData, $admin, $chef);
    }

    function processOrder($data, $admin, $chef)
    {
        $order = Order::create($data);

        dump('order placed');

        $order->update([
            'received_by' => $admin->id,
            'status' => OrderStatus::received->value
        ]);

        dump('order received');

        $order->update([
            'prepare_by' => $chef->id,
            'status' => OrderStatus::processing->value
        ]);

        dump('order assigned to chef');

        $order->update([
            'status' => OrderStatus::ready->value
        ]);

        dump('order is ready to be served');
    }
}
