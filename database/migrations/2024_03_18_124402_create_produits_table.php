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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc')->nullable();
            $table->string('couleur')->nullable();
            $table->string('dosage')->nullable();
            $table->string('prix_vente_ht')->nullable();
            $table->string('prix_vente_net_ht')->nullable();
            $table->string('prix_vente_ttc')->nullable();
            $table->string('taux_tva')->nullable();
            $table->string('code_barre')->nullable();
            $table->longText('photo')->nullable();
            $table->string('max_remise')->nullable();
            $table->boolean('active')->nullable();
            $table->unsignedBigInteger('categ_produit_id')->nullable();
            $table->foreign('categ_produit_id')
                ->references('id')
                ->on('categ_produits')
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
        Schema::dropIfExists('produits');
    }
};
