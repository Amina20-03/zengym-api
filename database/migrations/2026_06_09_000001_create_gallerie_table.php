<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallerie', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->string('photo');
            $table->unsignedBigInteger('categ_id')->nullable(); // FK vers photo_instructeur_categories
            $table->integer('ordre')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallerie');
    }
};
