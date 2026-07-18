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
        Schema::create('ligne_bon_entrees', function (Blueprint $table) {
            $table->id();

            $table->string('qte_entree')->nullable();
            $table->string('pu_prod_entree')->nullable();
            $table->string('total_ligne_entree')->nullable();
            $table->unsignedBigInteger('bon_entree_id')->nullable();
            $table->foreign('bon_entree_id')
                ->references('id')
                ->on('bon_entrees')
                ->onDelete('restrict')
                ->onUpdate('restrict');
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
        Schema::dropIfExists('ligne_bon_entrees');
    }
};
