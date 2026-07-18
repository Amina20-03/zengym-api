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
        Schema::create('formation_videos', function (Blueprint $table) {
            $table->id();
            $table->longText('photo')->nullable();
            $table->string('titre')->nullable();
            $table->string('desc')->nullable();
            $table->unsignedBigInteger('formation_id')->nullable();
            $table->foreign('formation_id')
                ->references('id')
                ->on('formations')
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
        Schema::dropIfExists('formation_videos');
    }
};
