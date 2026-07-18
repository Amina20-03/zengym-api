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
        Schema::create('formation_users', function (Blueprint $table) {
            $table->id();
            $table->date('date_validation')->nullable();
            $table->unsignedBigInteger('formation_id')->nullable();
            $table->foreign('formation_id')
                ->references('id')
                ->on('formations')
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
        Schema::dropIfExists('formation_users');
    }
};
