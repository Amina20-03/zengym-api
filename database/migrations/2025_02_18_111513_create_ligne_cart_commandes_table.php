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
        Schema::create('ligne_cart_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('qte')->nullable();
            $table->string('id_produit')->nullable();
            $table->string('id_cart_commande')->nullable();
            $table->string('code_produit')->nullable();
            $table->string('desc_produit')->nullable();
            $table->string('prix_produit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_cart_commandes');
    }
};
