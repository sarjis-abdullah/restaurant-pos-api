<?php

namespace Database\Seeders;

use App\Enums\TableStatus;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Floor;
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
            'company_id' => $company->id
        ]);

        $table = Table::create([
            "name" => "Table 1",
            "branch_id" => $branch->id,
            'company_id' => $company->id,
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
    }
}
