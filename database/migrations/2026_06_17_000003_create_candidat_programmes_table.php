<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->unsignedBigInteger('programme_id');
            $table->date('date_debut')->nullable();
            $table->string('statut')->default('en_cours'); // en_cours, termine, pause
            $table->timestamps();

            $table->foreign('candidat_id')->references('id')->on('candidats')->onDelete('cascade');
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
            $table->unique(['candidat_id', 'programme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_programmes');
    }
};
