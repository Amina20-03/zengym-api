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
        Schema::create('pourcentages', function (Blueprint $table) {
            $table->id();
            $table->string('pr_client')->nullable();
            $table->string('pr_prod')->nullable();
            $table->string('pr_formation')->nullable();
            $table->unsignedBigInteger('cat_inst_id')->nullable();
            $table->foreign('cat_inst_id')
                ->references('id')
                ->on('categ_instructeurs')
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
        Schema::dropIfExists('pourcentages');
    }
};
