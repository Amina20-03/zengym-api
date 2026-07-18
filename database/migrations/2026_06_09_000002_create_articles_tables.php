<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc');
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categ_id')->nullable();
            $table->string('titre');
            $table->text('contenu')->nullable();
            $table->string('photo')->nullable(); // chemin relatif storage/app/public/articles/
            $table->integer('ordre')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('categ_id')
                  ->references('id')->on('article_categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
};
