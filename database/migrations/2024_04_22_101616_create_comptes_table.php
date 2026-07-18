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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->date('datedernmodif')->nullable();
            $table->string('soldecpte')->nullable();
            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
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
        Schema::dropIfExists('comptes');
    }
};
