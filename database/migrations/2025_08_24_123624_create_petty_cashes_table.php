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
        Schema::create('petty_cashes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pettycash')->unique();
            $table->integer('amount');
            $table->integer('used_amount');
            $table->string('description');
            $table->enum('tipe', ['Operasional', 'Project', 'Perjalanan']);
            $table->enum('status', ['pending', 'dept_approved', 'finance_approved', 'paid', 'rejected']);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('dept_approved_by')->nullable();
            $table->unsignedBigInteger('finance_approved_by')->nullable();
            $table->dateTime('tanggal_pencairan')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('dept_approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('finance_approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cashes');
    }
};
