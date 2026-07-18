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
        Schema::create('user_videos', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('titre')->nullable();
            $table->string('desc')->nullable();
            $table->unsignedBigInteger('categ_id')->nullable();
            $table->foreign('categ_id')
                ->references('id')
                ->on('user_ph_vid_doc_categories')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_videos');
    }
};
