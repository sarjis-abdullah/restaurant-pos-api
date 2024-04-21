<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instruments_supported', function (Blueprint $table) {
            $table->increments('id');
            $table->string('isin', 12)->unique();
            $table->string('wkn', 6)->nullable();
            $table->unsignedInteger('instrument_type_id');
            $table->string('name', 255);
            $table->foreign('instrument_type_id')->references('id')->on('instrument_types');
            $table->timestamps();
        });

        // insert some supported instruments
        DB::table('instruments_supported')->insert([
            ['isin' => 'LU1329517301', 'wkn' => 'A2ABGS', 'instrument_type_id' => 4, 'name' => 'Arabesque Q3.17 SICAV - Global ESG Momentum Flexible Allocation R EUR', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'LU1150255971', 'wkn' => 'A14368', 'instrument_type_id' => 4, 'name' => 'BNP Paribas Islamic Fund Hilal Income Classic Cap', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE00B4ZJ4634', 'wkn' => 'A1JJUY', 'instrument_type_id' => 4, 'name' => 'Comgest Growth Europe S EUR Acc', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'LU2458330086', 'wkn' => 'A3DJW2', 'instrument_type_id' => 4, 'name' => 'Franklin Shariah Technology Fund A (acc) USD', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE000UOXRAM8', 'wkn' => 'A3C6Z0', 'instrument_type_id' => 3, 'name' => 'Invesco Markets II plc - Dow Jones Islamic Global Developed Markets UCITS ETF', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE00B27YCP72', 'wkn' => 'A0NA47', 'instrument_type_id' => 3, 'name' => 'iShares MSCI Emerging Markets Islamic UCITS ETF USD (Dist)', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE00B296QM64', 'wkn' => 'A0NA48', 'instrument_type_id' => 3, 'name' => 'iShares MSCI USA Islamic UCITS ETF USD (Dist) Share Class', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE00B27YCN58', 'wkn' => 'A0NA46', 'instrument_type_id' => 3, 'name' => 'iShares MSCI World Islamic UCITS ETF USD (Dist) Share Class', 'created_at' => now(), 'updated_at' => now()],
            ['isin' => 'IE00BMYMHS24', 'wkn' => 'A2P5A6', 'instrument_type_id' => 3, 'name' => 'Saturna Al-Kawthar Global Focused Equity UCITS ETF Acc', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments_supported');
    }
};
