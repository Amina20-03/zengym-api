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
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('tel1')->nullable();
            $table->string('tel2')->nullable();
            $table->string('email')->nullable();
            $table->string('adr')->nullable();
            $table->string('cp')->nullable();
            $table->string('mt_affiliation')->nullable();
            $table->unsignedBigInteger('categ_candidat_id')->nullable();
            $table->foreign('categ_candidat_id')
                ->references('id')
                ->on('categ_candidats')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->unsignedBigInteger('salle_sport_id')->nullable();
            $table->foreign('salle_sport_id')
                ->references('id')
                ->on('salle_de_sports')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
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
        Schema::dropIfExists('candidats');
    }
};
