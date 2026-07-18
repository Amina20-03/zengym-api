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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('mail')->nullable();
            $table->string('adresse')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('candidat_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('representant_id')->nullable();
            $table->foreign('representant_id')
                ->references('id')
                ->on('representants')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->timestamps();
        });


        DB::table('users')->insert([
            [

                'nom' => 'admin',
                'prenom' => 'admin',
                'email' => 'admin',
                'role' => 'ADMIN',
                'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make('1234'),
                'admin_id' => '1',
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
        Schema::dropIfExists('users');
    }
};
