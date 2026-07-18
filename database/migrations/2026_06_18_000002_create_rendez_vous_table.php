<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->unsignedBigInteger('instructeur_id');
            $table->date('date');
            $table->time('heure_deb');
            $table->time('heure_fin');
            $table->string('type')->default('Suivi');  // Suivi, Séance, Consultation
            $table->text('note')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente, accepte, refuse
            $table->text('motif_refus')->nullable();
            $table->timestamps();

            $table->foreign('candidat_id')->references('id')->on('candidats')->onDelete('cascade');
            $table->foreign('instructeur_id')->references('id')->on('instructeurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
