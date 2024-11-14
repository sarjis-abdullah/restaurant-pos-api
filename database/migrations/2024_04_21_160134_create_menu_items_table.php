<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->decimal('quantity');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->boolean('tax_included')->default(false);
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('type')->default('piece'); // 'piece', 'set_menu', 'mix_of_platter', etc.
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->integer('serves')->default(1);
            $table->boolean('allow_other_discount')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
