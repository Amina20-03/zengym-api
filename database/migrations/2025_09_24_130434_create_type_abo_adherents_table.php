<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeAboAdherentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_abo_adherents', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('des')->nullable();
            $table->string('periode')->nullable();
            $table->string('frais')->nullable();
            $table->string('remise')->nullable();
            $table->string('frais_ap_remise')->nullable();
            $table->boolean('seance_essai')->nullable();
            $table->string('frais_seance_essai')->nullable();
            $table->string('nbr_pers_limit')->nullable();
            $table->unsignedBigInteger('categ_abo_id')->nullable();
            $table->foreign('categ_abo_id')
                ->references('id')
                ->on('categ_type_abo_adherents')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_abo_adherents');
    }
}
