<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategTypeAboAdherentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categ_type_abo_adherents', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->binary('photo')->nullable();
            $table->string('desc')->nullable();
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
        Schema::dropIfExists('categ_type_abo_adherents');
    }
}
