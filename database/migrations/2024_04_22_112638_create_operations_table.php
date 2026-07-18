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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->string('montant')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('compte_id')->nullable();
            $table->foreign('compte_id')
                ->references('id')
                ->on('comptes')
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
        Schema::dropIfExists('operations');
    }
};
