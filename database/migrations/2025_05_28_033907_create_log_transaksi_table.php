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
        Schema::create('log_transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('tipe_item', ['bahan_baku', 'produk_jadi']);
            $table->integer('item_id');
            $table->string('tipe_transaksi', 50);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_transaksi');
    }
};
