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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc')->nullable();
            $table->string('frais')->nullable();
            $table->string('emplacement')->nullable();
            $table->date('date')->nullable();
            $table->time('hdeb')->nullable();
            $table->time('hfin')->nullable();
            $table->string('titre')->nullable();
            $table->string('sujet')->nullable();
            $table->string('devise')->nullable();
            $table->string('user_id')->nullable();
            $table->boolean('approuver')->nullable();
            $table->boolean('realiser')->nullable();
            $table->unsignedBigInteger('categ_cours_id')->nullable();
            $table->foreign('categ_cours_id')
                ->references('id')
                ->on('categ_cours')
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
        Schema::dropIfExists('cours');
    }
};
