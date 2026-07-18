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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('raison_soc_fourn')->nullable();
            $table->string('contact_fourn')->nullable();
            $table->string('tel1_fourn')->nullable();
            $table->string('tel2_fourn')->nullable();
            $table->string('mf_fourn')->nullable();
            $table->string('rc_fourn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
