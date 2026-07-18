<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructeur_id');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('duree_semaines')->nullable();      // ex: 12
            $table->string('niveau')->nullable();                // Débutant, Intermédiaire, Avancé, Tous niveaux
            $table->string('photo')->nullable();                 // storage path WebP
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->foreign('instructeur_id')
                  ->references('id')->on('instructeurs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
