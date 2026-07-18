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
        Schema::create('vente_abos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->string('montant_ht')->nullable();
            $table->string('montant_ttc')->nullable();
            $table->string('taux_tva')->nullable();
            $table->string('paiement')->nullable();
            $table->boolean('solder')->nullable();
            $table->boolean('dernier')->nullable();
            $table->date('date_deb')->nullable();
            $table->date('date_fin')->nullable();
            $table->unsignedBigInteger('type_abo_id')->nullable();
            $table->foreign('type_abo_id')
                ->references('id')
                ->on('type_abos')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('instructeur_id')->nullable();
            $table->foreign('instructeur_id')
                ->references('id')
                ->on('instructeurs')
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
        Schema::dropIfExists('vente_abos');
    }
};
