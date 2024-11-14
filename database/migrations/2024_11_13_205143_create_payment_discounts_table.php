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
        Schema::create('payment_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('type'); //promo, membership, instant discount, menu item etc.
            $table->decimal('amount');
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_discounts');
    }
};
