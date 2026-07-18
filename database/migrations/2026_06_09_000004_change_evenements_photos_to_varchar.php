<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vider les anciennes photos base64 (elles ne peuvent pas être converties sans les données originales)
        \Illuminate\Support\Facades\DB::table('evenements_photos')->truncate();

        Schema::table('evenements_photos', function (Blueprint $table) {
            // Changer binary → varchar pour stocker le chemin storage
            $table->string('photo', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('evenements_photos', function (Blueprint $table) {
            $table->binary('photo')->nullable()->change();
        });
    }
};
