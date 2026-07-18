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
        Schema::create('salle_de_sports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('nom')->nullable();
            $table->string('adresse')->nullable();
            $table->string('tel1')->nullable();
            $table->string('tel2')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salle_de_sports');
    }
};
