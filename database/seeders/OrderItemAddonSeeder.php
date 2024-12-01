<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemAddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_item_addons')->insert([
            ['order_item_id' => 1, 'addon_id' => 1, 'quantity' => 1, 'total_amount' => 1.50, 'created_at' => now(), 'updated_at' => now()],
            ['order_item_id' => 1, 'addon_id' => 4, 'quantity' => 2, 'total_amount' => 2.40, 'created_at' => now(), 'updated_at' => now()],
            ['order_item_id' => 2, 'addon_id' => 2, 'quantity' => 1, 'total_amount' => 2.00, 'created_at' => now(), 'updated_at' => now()],
            ['order_item_id' => 2, 'addon_id' => 3, 'quantity' => 1, 'total_amount' => 1.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
