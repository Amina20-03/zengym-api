<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_sante_candidat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->date('date_suivi');
            $table->decimal('poids', 5, 1)->nullable();           // kg
            $table->decimal('taille', 5, 1)->nullable();          // cm
            $table->decimal('imc', 4, 1)->nullable();             // calculé auto
            $table->decimal('glycemie', 4, 2)->nullable();        // g/L
            $table->string('tension_arterielle')->nullable();      // ex: 12/8
            $table->integer('frequence_cardiaque')->nullable();    // bpm
            $table->integer('niveau_stress')->nullable();          // /10
            $table->text('objectifs')->nullable();
            $table->text('progression')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('candidat_id')->references('id')->on('candidats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_sante_candidat');
    }
};
