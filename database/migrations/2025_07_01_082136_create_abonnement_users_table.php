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
        Schema::create('abonnement_users', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->date('date_paie')->nullable();
            $table->string('ref')->nullable();
            $table->boolean('status_paie')->nullable();
            $table->string('type_paie')->nullable();
            $table->string('vente_abo_id')->nullable();
            $table->date('date_deb')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('active')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('type_abo_id')->nullable();
            $table->foreign('type_abo_id')
                ->references('id')
                ->on('type_abos')
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
        Schema::dropIfExists('abonnement_users');
    }
};
