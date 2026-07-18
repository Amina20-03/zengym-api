<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->string('note_max')->nullable();
            $table->string('nbr_total_quest')->nullable();
            $table->timestamps();
        });
        DB::table('examens')->insert([
          [
              "id"=>1,
              "titre"=>"Examen QCM pour Certification Instructeur ZENGYM",
              "note_max"=>"20",
              "nbr_total_quest"=>"20",
          ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
