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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->time('heure')->nullable();
            $table->string('nbr_participant')->nullable();
            $table->string('titre')->nullable();
            $table->string('sujet')->nullable();
            $table->string('desc')->nullable();
            $table->string('frais')->nullable();
            $table->string('devise')->nullable();
            $table->string('lieu')->nullable();
            $table->string('nbr_place_max')->nullable();
            $table->string('contact')->nullable();
            $table->boolean('encaisse')->nullable();
            $table->boolean('approuver')->nullable();
            $table->boolean('realiser')->nullable();
            $table->boolean('enligne')->nullable();
            $table->string('user_id')->nullable();
            $table->string('path_livret_scientifique')->nullable();
            $table->string('path_presentation_power_point')->nullable();
            $table->string('path_video_basicone')->nullable();
            $table->string('enligne_url')->nullable();
            $table->string('path_prog_de_formation')->nullable();
            $table->unsignedBigInteger('categ_formation_id')->nullable();
            $table->foreign('categ_formation_id')
                ->references('id')
                ->on('categ_formations')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->string('organisateur_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
