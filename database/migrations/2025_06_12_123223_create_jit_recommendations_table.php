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
        Schema::create('jit_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('item_type'); 
       
            $table->unsignedBigInteger('item_id'); 
          
            $table->enum('recommendation_type', ['PRODUKSI', 'PEMBELIAN']); 
            $table->decimal('recommended_quantity', 10, 2);
      
            $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])->default('PENDING'); 
            $table->date('analysis_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jit_recommendations');
    }
};
