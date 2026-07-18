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
        Schema::create('evenement_candidats', function (Blueprint $table) {
            $table->id();
            $table->string('methode_paiement')->nullable();
            $table->boolean('paiement_status')->nullable();
            $table->string('ref')->nullable();
            $table->date('date_validation')->nullable();
            $table->string('event_id')->nullable();
            $table->string('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement_candidats');
    }
};
