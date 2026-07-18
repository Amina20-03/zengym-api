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
        Schema::create('cart_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('prix_total')->nullable();
            $table->string('ref')->nullable();
            $table->date('date')->nullable();
            $table->string('paiement_par')->nullable();
            $table->boolean('paiement_status')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_commandes');
    }
};
