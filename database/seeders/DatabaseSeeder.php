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
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
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
    public function runPurchaseProduct()
    {
        $products = Product::query()->pluck('id');   // Assuming products exist
//        $units = ['kg', 'g', 'pcs', 'box', 'set'];     // Possible units

        $purchaseAbleItems = collect([]);
        $finalTotal = 0;
        $finalDiscount = 0;
        $finalTax = 0;
        $finalCost = 0;
        for ($i = 0; $i < 10; $i++) {
            $discountType = ['flat', 'percentage'][array_rand(['flat', 'percentage'])];
            $taxType = ['flat', 'percentage'][array_rand(['flat', 'percentage'])];
            $quantity = rand(10, 11);
            $purchasePrice = rand(10,11)*10;
            $sellingPrice = rand(20,21)*10;
            $taxAmount = $taxType == 'percentage' ? $purchasePrice * (5/100) : 5;
            $discountAmount = $discountType == 'percentage' ? $purchasePrice * (2/100) : 2;
            $shippingCost = rand(20, 21) * 10;
            $subtotal = ($quantity * $purchasePrice) + $taxAmount - $discountAmount;
            $finalTotal += $subtotal;
            $finalDiscount += $discountAmount;
            $finalTax += $taxAmount;
            $purchaseAbleItems->push([
                'product_id'     => $products->random(),
                'quantity'       => $quantity,
                'purchase_price' => $purchasePrice,
                'selling_price'  => $sellingPrice,
                'tax_amount'     => $taxAmount,
//                'shipping_cost'  => $shippingCost,
                'discount_amount'=> $discountAmount,
                'discount_type'  => $discountType,
                'tax_type'       => $taxType,
                'subtotal'       => $subtotal,
            ]);
        }

        $suppliers = Supplier::query()->pluck('id');
        $purchaseId = Purchase::create([
            'supplier_id'    => $suppliers->random(),
            'purchase_date'  => now()->subDays(rand(1, 30)), // Random date within the last 30 days
            'total_amount'   => $finalTotal,     // Random amount between 50.00 and 500.00
            'discount_amount' => $finalDiscount,         // Random discount
            'tax_amount'      => $finalTax,       // Random tax
            'shipping_cost'   => $finalCost,        // Random shipping cost
            'status'          => ['pending', 'completed', 'cancelled'][array_rand(['pending', 'completed', 'cancelled'])],
        ])->id;

        $purchaseAbleItems = array_map(function ($item) use ($purchaseId, $shippingCost, $finalTotal) {
            $item['purchase_id'] = $purchaseId;
            $proportionalShipping = ($item['subtotal'] / $finalTotal) * $shippingCost;
            $item['allocated_shipping_cost'] = $proportionalShipping;

            // Adjust the subtotal to include the allocated shipping cost
            $item['subtotal'] += $proportionalShipping;
            return $item;
        }, $purchaseAbleItems->toArray());

        PurchaseProduct::insert($purchaseAbleItems);


//        foreach ($purchases as $purchase_id) {
//            // Add 3-5 products for each purchase
//            for ($i = 0; $i < rand(3, 5); $i++) {
//                $quantity = rand(1, 100);
//                $purchase_price = rand(100, 5000) / 100;
//                $selling_price = $purchase_price + rand(50, 200) / 100;
//                $tax_amount = $purchase_price * (rand(5, 15) / 100); // 5% to 15% tax
//                $discount_amount = $purchase_price * (rand(0, 10) / 100); // 0% to 10% discount
//
//                PurchaseProduct::create([
//                    'purchase_id'    => $purchase_id,
//                    'product_id'     => $products[array_rand($products)],
//                    'quantity'       => $quantity,
//                    'purchase_price' => $purchase_price,
//                    'selling_price'  => $selling_price,
//                    'tax_amount'     => $tax_amount,
//                    'discount_amount'=> $discount_amount,
//                    'discount_type'  => ['flat', 'percentage'][array_rand(['flat', 'percentage'])],
//                    'tax_type'       => ['flat', 'percentage'][array_rand(['flat', 'percentage'])],
//                    'subtotal'       => ($quantity * $purchase_price) + $tax_amount - $discount_amount,
//                ]);
//            }
//        }
    }
    public function runPurchase()
    {
        $suppliers = Supplier::pluck('id')->toArray(); // Assuming suppliers exist

        // Seed 10 purchases
        for ($i = 0; $i < 10; $i++) {
            Purchase::create([
                'supplier_id'    => $suppliers[array_rand($suppliers)],
                'purchase_date'  => now()->subDays(rand(1, 30)), // Random date within the last 30 days
                'total_amount'   => rand(5000, 50000) / 100,     // Random amount between 50.00 and 500.00
                'discount_amount' => rand(0, 500) / 100,         // Random discount
                'tax_amount'      => rand(50, 1000) / 100,       // Random tax
                'shipping_cost'   => rand(0, 1000) / 100,        // Random shipping cost
                'status'          => ['pending', 'completed', 'cancelled'][array_rand(['pending', 'completed', 'cancelled'])],
            ]);
        }
    }
    private function createSupplier()
    {
        Supplier::factory()->count(10)->create();
        Product::factory()->count(20)->create();
    }
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->createSupplier();
//        $this->runPurchase();
        $this->runPurchaseProduct();
//        Product::factory()->count(20)->create();
        return
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
                "name" => "Table ".$index . ' '.$faker->name,
                "branch_id" => $branch->id,
                "floor_id" => $floor->id,
            ]);
        }

        foreach (range(1, 3) as $index) {
            Tax::create([
                'name' => 'Tax '.$faker->name,
                "rate" => 5,
                "branch_id" => $branch->id,
                "type" => $index < 2 ? 'flat' : 'percentage',
            ]);
        }

        $this->call([
            CategorySeeder::class,
            MenuSeeder::class,
            VariantSeeder::class,
            AddonSeeder::class,
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
            'total_amount' => $menuItem->price*rand(1,4),
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
