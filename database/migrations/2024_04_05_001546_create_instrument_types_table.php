<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instrument_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('description', 255);
            $table->timestamps();
        });

        // insert with created_at and updated_at
        DB::table('instrument_types')->insert([
            ['id' => 1, 'name' => 'stock', 'description' => 'Stock', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'bond', 'description' => 'Bond', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'etf', 'description' => 'Exchange Traded Fund', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'fund', 'description' => 'Fund', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'index', 'description' => 'Index', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'currency', 'description' => 'Currency', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'commodity', 'description' => 'Commodity', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'crypto', 'description' => 'Cryptocurrency', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'future', 'description' => 'Future', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'option', 'description' => 'Option', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'warrant', 'description' => 'Warrant', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'certificate', 'description' => 'Certificate', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'other', 'description' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_types');
    }
};
