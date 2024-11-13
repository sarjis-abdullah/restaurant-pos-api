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
            $table->decimal('total_amount');
            $table->decimal('paid_amount');
            $table->string('method')->default(PaymentMethod::cash->value);
            $table->string('status')->default(PaymentStatus::pending->value);
            $table->string('reference_number')->nullable();
            $table->string('transaction_number')->nullable();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(User::class, 'received_by');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
//            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
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
