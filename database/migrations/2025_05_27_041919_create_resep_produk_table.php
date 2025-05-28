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
        Schema::create('resep_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_jadi_id')
                ->constrained('produk_jadi')
                ->onDelete('cascade');
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_baku')
                ->onDelete('cascade');
            $table->decimal('jumlah_dibutuhkan', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_produk');
    }
};
