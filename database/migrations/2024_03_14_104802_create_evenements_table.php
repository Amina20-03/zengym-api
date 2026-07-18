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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->longText('desc')->nullable();
            $table->boolean('fait')->nullable();
            $table->date('date_deb')->nullable();
            $table->date('date_fin')->nullable();
            $table->time('heure_deb')->nullable();
            $table->time('heure_fin')->nullable();
            $table->string('nbr_participant')->nullable();
            $table->string('nbr_place_dispo')->nullable();
            $table->string('nbr_place_restant')->nullable();
            $table->string('titre')->nullable();
            $table->longText('sujet')->nullable();
            $table->string('participant_a_levennement')->nullable();
            $table->string('participant_non_inscrit')->nullable();
            $table->string('salle')->nullable();
            $table->string('contacte')->nullable();
            $table->string('info_sur_lieu')->nullable();
            $table->string('frais')->nullable();
            $table->string('devise')->nullable();
            $table->boolean('approuver')->nullable();
            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('type_even_id')->nullable();
            $table->foreign('type_even_id')
                ->references('id')
                ->on('type_evenements')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('pays_id')->nullable();
            $table->foreign('pays_id')
                ->references('id')
                ->on('pays')
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
        Schema::dropIfExists('evenements');
    }
};
