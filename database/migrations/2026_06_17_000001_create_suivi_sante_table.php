<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_sante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->date('date_suivi');
            $table->decimal('poids', 5, 1)->nullable();           // kg
            $table->integer('tour_de_taille')->nullable();         // cm
            $table->string('tension_arterielle')->nullable();       // ex: 12/8
            $table->integer('frequence_cardiaque')->nullable();     // bpm
            $table->decimal('glycemie', 4, 2)->nullable();         // g/L
            $table->integer('saturation_oxygene')->nullable();     // %
            $table->integer('niveau_energie')->nullable();          // /10
            $table->integer('niveau_stress')->nullable();           // /10
            $table->integer('qualite_sommeil')->nullable();         // /10
            $table->string('presence')->nullable();                 // Présent / Absent
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('candidat_id')
                  ->references('id')->on('candidats')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_sante');
    }
};
