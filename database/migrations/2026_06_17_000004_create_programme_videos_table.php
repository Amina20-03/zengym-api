<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programme_id');
            $table->string('lib')->nullable();
            $table->string('path');  // storage path
            $table->timestamps();

            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_videos');
    }
};
