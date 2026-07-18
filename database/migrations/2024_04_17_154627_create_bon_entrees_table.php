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
        Schema::create('bon_entrees', function (Blueprint $table) {
            $table->id();
            $table->string('code_be')->nullable();
            $table->date('date_be')->nullable();
            $table->string('totah_ht_be')->nullable();
            $table->string('total_ttc_be')->nullable();
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->foreign('fournisseur_id')
                ->references('id')
                ->on('fournisseurs')
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
        Schema::dropIfExists('bon_entrees');
    }
};
