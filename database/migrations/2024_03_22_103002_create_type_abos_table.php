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
        Schema::create('type_abos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc')->nullable();
            $table->string('nb_mois')->nullable();
            $table->string('prix_ttc')->nullable();
            $table->string('taux_tva')->nullable();
            $table->string('prix_ht')->nullable();
            $table->unsignedBigInteger('categ_abo_id')->nullable();
            $table->foreign('categ_abo_id')
                ->references('id')
                ->on('categ_abos')
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
        Schema::dropIfExists('type_abos');
    }
};
