<?php

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\User;
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
            $table->decimal('total_amount')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->decimal('due_amount')->default(0);
            $table->decimal('tax_amount')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->string('method')->nullable();
            $table->string('type')->nullable(); //partial payment, full payment
            $table->string('status')->default(PaymentStatus::pending->value);
            $table->string('reference_number')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('transaction_id');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(User::class, 'received_by');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
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
