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
        Schema::create('ligne_bon_sorties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prod_id')->nullable();
            $table->foreign('prod_id')
                ->references('id')
                ->on('produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->string('qte_sortie')->nullable();
            $table->string('pu_sortie')->nullable();
            $table->string('total_ligne_st')->nullable();
            $table->unsignedBigInteger('IDBSORTIE')->nullable();
            $table->foreign('IDBSORTIE')
                ->references('id')
                ->on('bon_sorties')
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
        Schema::dropIfExists('ligne_bon_sorties');
    }
};
