<?php

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->dateTime('date');
            $table->decimal('amount');
            $table->decimal('receivedAmount')->nullable();
            $table->decimal('changedAmount')->nullable();
            $table->string('method')->default(PaymentMethod::cash->value);
            $table->string('status')->default(PaymentStatus::pending->value);
            $table->string('referenceNumber')->nullable();
            $table->string('transactionNumber')->nullable();
            $table->foreignId('company_id')->constrained('companies', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
