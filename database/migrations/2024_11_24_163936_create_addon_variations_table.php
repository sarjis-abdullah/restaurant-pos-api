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
        Schema::create('addon_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id')->constrained('addons')->onDelete('cascade');
            $table->string('attribute');
            $table->string('value');
            $table->decimal('price_modifier', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_variations');
    }
};
