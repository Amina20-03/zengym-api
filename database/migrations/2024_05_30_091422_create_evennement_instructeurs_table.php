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
        Schema::create('evennement_instructeurs', function (Blueprint $table) {
            $table->id();
            $table->date('date_validation')->nullable();
            $table->unsignedBigInteger('evennement_id')->nullable();
            $table->foreign('evennement_id')
                ->references('id')
                ->on('evenements')
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
        Schema::dropIfExists('evennement_instructeurs');
    }
};
