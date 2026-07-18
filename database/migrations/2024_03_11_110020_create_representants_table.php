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
        Schema::create('representants', function (Blueprint $table) {
            $table->id();
            $table->string('raison_social')->nullable();
            $table->string('contact')->nullable();
            $table->string('mf')->nullable();
            $table->string('rc')->nullable();
            $table->string('localisation')->nullable();
            $table->unsignedBigInteger('categ_rep_id')->nullable();
            $table->foreign('categ_rep_id')
                ->references('id')
                ->on('categ_reps')
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
        Schema::dropIfExists('representants');
    }
};
