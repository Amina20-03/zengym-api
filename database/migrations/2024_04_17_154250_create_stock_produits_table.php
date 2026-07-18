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
        Schema::create('stock_produits', function (Blueprint $table) {
            $table->id();
            $table->string('qte_stk')->nullable();
            $table->unsignedBigInteger('prod_id')->nullable();
            $table->foreign('prod_id')
                ->references('id')
                ->on('produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_produits');
    }
};
