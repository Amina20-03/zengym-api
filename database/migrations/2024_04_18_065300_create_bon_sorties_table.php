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
        Schema::create('bon_sorties', function (Blueprint $table) {
            $table->id();
            $table->string('code_bs')->nullable();
            $table->string('date_bs')->nullable();
            $table->string('total_ht_bs')->nullable();
            $table->string('total_ttc_bs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_sorties');
    }
};
