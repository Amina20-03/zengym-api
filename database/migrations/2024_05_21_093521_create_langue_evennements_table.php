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
        Schema::create('langue_evennements', function (Blueprint $table) {
            $table->id();
            $table->string('langue')->nullable();
            $table->unsignedBigInteger('evennement_id')->nullable();
            $table->foreign('evennement_id')
                ->references('id')
                ->on('evenements')
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
        Schema::dropIfExists('langue_evennements');
    }
};
