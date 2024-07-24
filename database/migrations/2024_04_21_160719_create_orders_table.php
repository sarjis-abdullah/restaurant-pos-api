<?php

use App\Enums\OrderStatus;
use App\Enums\OrderType;
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
            $table->unsignedBigInteger('order_by');
            $table->unsignedBigInteger('table_id');
            $table->dateTime('date');
            $table->string('status')->default(OrderStatus::processing->value);
            $table->string('type')->default(OrderType::dine_in->value);
            $table->dateTime('pickup_date')->nullable();
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
