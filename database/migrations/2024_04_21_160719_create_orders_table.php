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
            $table->foreignIdFor(User::class, 'taken_by')->nullable();
            $table->foreignIdFor(User::class, 'prepare_by')->nullable();
            $table->foreignIdFor(User::class, 'received_by')->nullable();
            $table->foreignIdFor(MenuItem::class, 'menu_item_id');
            $table->foreignIdFor(Table::class, 'table_id');
            $table->string('status')->default(OrderStatus::requested->value);
            $table->string('type')->default(OrderType::dine_in->value);
            $table->dateTime('pickup_date')->nullable();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies', 'id')->onDelete('cascade');
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
