<?php

use App\Enums\TableStatus;
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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
//            $table->integer('max_seat');
//            $table->integer('min_seat')->nullable();
            $table->string('status')->default(TableStatus::available->value);
            $table->dateTime('booking_from')->nullable();
            $table->dateTime('booking_to')->nullable();
//            $table->foreignIdFor(User::class, 'request_by')->nullable();
//            $table->foreignIdFor(User::class, 'received_by')->nullable();
            $table->foreignId('floor_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
