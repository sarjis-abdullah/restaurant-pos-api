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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_product_id')->constrained('purchase_products')->onDelete('cascade');
//            $table->decimal('unit_cost');
//            $table->decimal('purchase_price');
//            $table->decimal('selling_price');
//            $table->decimal('tax')->default(0);
//            $table->enum('tax_type', ['percentage', 'flat'])->default('percentage');
//            $table->enum('discount_type', ['percentage', 'flat'])->default('percentage');
//            $table->decimal('discount')->default(0);
            $table->decimal('quantity')->default(0);
//            $table->decimal('shipping_cost')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
