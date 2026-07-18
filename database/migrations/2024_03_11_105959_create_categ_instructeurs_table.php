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
        Schema::create('categ_instructeurs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc')->nullable();
            $table->string('ordre')->nullable();
            $table->timestamps();
        });
        DB::table('categ_instructeurs')->insert([
            [

                'code' => 'CI_000',
                'desc' => 'COACH',
                'ordre' => '0',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CI_001',
                'desc' => 'MASTER TRAINER',
                'ordre' => '1',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CI_002',
                'desc' => 'FORMATEUR',
                'ordre' => '2',

                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categ_instructeurs');
    }
};
