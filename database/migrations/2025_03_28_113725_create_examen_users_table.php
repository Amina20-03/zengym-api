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
        Schema::create('examen_users', function (Blueprint $table) {
            $table->id();
            $table->string('id_examen')->nullable();
            $table->string('id_user')->nullable();
            $table->string('score')->nullable();
            $table->string('res_examen')->nullable();
            $table->string('commentaire')->nullable();
            $table->binary('certificat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_users');
    }
};
