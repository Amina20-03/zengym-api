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
        Schema::create('quest_users', function (Blueprint $table) {
            $table->id();
            $table->integer('id_quest')->nullable();
            $table->integer('id_user')->nullable();
            $table->boolean('status_rep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_users');
    }
};
