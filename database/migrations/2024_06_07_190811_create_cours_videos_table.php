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
        Schema::create('cours_videos', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('titre')->nullable();
            $table->string('desc')->nullable();
            $table->unsignedBigInteger('cours_id')->nullable();
            $table->foreign('cours_id')
                ->references('id')
                ->on('cours')
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
        Schema::dropIfExists('cours_videos');
    }
};
