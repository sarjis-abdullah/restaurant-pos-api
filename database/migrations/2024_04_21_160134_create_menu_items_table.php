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
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('type')->default('piece'); // 'piece', 'set_menu', 'mix_of_platter', etc.
            $table->text('description')->nullable();
//            $table->string('image_url')->nullable(); // should have attachments
            $table->text('ingredients')->nullable();
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->integer('serves')->default(1);
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
