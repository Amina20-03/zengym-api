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
        Schema::create('ligne_vente_prods', function (Blueprint $table) {
            $table->id();
            $table->string('qte')->nullable();
            $table->string('pu_vente')->nullable();
            $table->string('pu_net_ht_vente')->nullable();
            $table->string('paiement_par')->nullable();
            $table->boolean('paiement_status')->nullable();
            $table->string('remise')->nullable();
            $table->unsignedBigInteger('prod_id')->nullable();
            $table->foreign('prod_id')
                ->references('id')
                ->on('produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->unsignedBigInteger('vente_prod_id')->nullable();
            $table->foreign('vente_prod_id')
                ->references('id')
                ->on('vente_prods')
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
        Schema::dropIfExists('ligne_vente_prods');
    }
};
