<?php

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\MenuItem;
use App\Models\Table;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'order_by')->nullable();
            $table->foreignIdFor(User::class, 'prepare_by')->nullable();
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(Table::class, 'table_id')->nullable();
            $table->string('status')->default(OrderStatus::requested->value);
            $table->string('type')->default(OrderType::dine_in->value);
            $table->dateTime('pickup_date')->nullable();
            $table->decimal('total_amount')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->decimal('tax_amount')->default(0);
            $table->decimal('addons_total')->default(0);
            $table->dateTime('order_date');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
