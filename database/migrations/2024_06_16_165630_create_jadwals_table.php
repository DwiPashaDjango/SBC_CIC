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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id');
            $table->foreignId('stands_id')->nullable();
            $table->foreignId('kategori_products_id');
            $table->date('tgl_penjualan');
            $table->date('tgl_akhir');
            $table->enum('status', ['pending', 'paid', 'tidak', 'completed'])->default('pending');
            $table->enum('is_repeat', ['ya', 'tidak'])->default('ya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
