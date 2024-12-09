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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Payment amount
            $table->string('payment_method'); // e.g., cash, visa, bank transfer, etc.
            $table->string('transaction_reference')->nullable(); // For card or bank transactions
            $table->string('status')->default('pending'); // e.g., completed, pending, failed
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
