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
        Schema::create('passage_de_grades', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('des')->nullable();
            $table->string('nbr_event')->nullable();
            $table->string('nbr_masterclass')->nullable();
            $table->unsignedBigInteger('categ_instructeur_id_1')->nullable();
            $table->foreign('categ_instructeur_id_1')
                ->references('id')
                ->on('categ_instructeurs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('categ_instructeur_id_2')->nullable();
            $table->foreign('categ_instructeur_id_2')
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
        Schema::dropIfExists('passage_de_grades');
    }
};
