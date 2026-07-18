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
        Schema::create('instructeurs', function (Blueprint $table) {
            $table->id();
            $table->string('code_instr')->nullable();
            $table->string('profession')->nullable();
            $table->string('commentaire')->nullable();
            $table->string('sexe')->nullable();
            $table->date('date_naiss')->nullable();
            $table->longText('photo')->nullable();
            $table->string('cin')->nullable();
            $table->boolean('diplome')->nullable();
            $table->unsignedBigInteger('pays_id')->nullable();
            $table->foreign('pays_id')
                ->references('id')
                ->on('pays')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('categ_instructeur_id')->nullable();
            $table->foreign('categ_instructeur_id')
                ->references('id')
                ->on('categ_instructeurs')
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
        Schema::dropIfExists('instructeurs');
    }
};
