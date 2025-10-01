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
        Schema::create('petty_cash_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pettycash_id');
            $table->string('kode_pettycash_details')->unique();
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('pcs');
            $table->integer('unit_price');
            $table->integer('total_price');
            $table->string('note');
            $table->timestamps();

            $table->foreign('pettycash_id')->references('id')->on('petty_cashes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_details');
    }
};
