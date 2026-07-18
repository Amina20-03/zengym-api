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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question')->nullable();
            $table->string('point')->nullable();
            $table->integer('id_examen')->nullable();
            $table->string('id_user')->nullable();
            $table->boolean('status_rep')->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('questions')->insert(
            [
                ["id"=>1, "question"=>"Quels muscles sont principalement sollicites dans les exercices ZENGYM debout ?", "point"=>"1", "id_examen"=>1],
                ["id"=>2, "question"=>"Lors d'un effort modere, quel systeme energetique est dominant ?", "point"=>"1", "id_examen"=>1],
                ["id"=>3, "question"=>"Quel est l'effet psychologique principal du ZENGYM ?", "point"=>"1", "id_examen"=>1],
                ["id"=>4, "question"=>"La respiration profonde utilisee dans ZENGYM agit sur :", "point"=>"1", "id_examen"=>1],
                ["id"=>5, "question"=>"Quelle est la duree recommandee pour les exercices ZENGYM debout ?", "point"=>"1", "id_examen"=>1],
                ["id"=>6, "question"=>"La filiere energetique aerobie produit de l'energie :", "point"=>"1", "id_examen"=>1],
                ["id"=>7, "question"=>"Quels sont les bienfaits physiques de ZENGYM ?", "point"=>"1", "id_examen"=>1],
                ["id"=>8, "question"=>"Quelle est la principale hormone influencee par la pratique ZENGYM ?", "point"=>"1", "id_examen"=>1],
                ["id"=>9, "question"=>"Dans ZENGYM, les exercices statiques :", "point"=>"1", "id_examen"=>1],
                ["id"=>10, "question"=>"La musique utilisee pendant les seances ZENGYM :", "point"=>"1", "id_examen"=>1],
                ["id"=>11, "question"=>"La posture et l'equilibre dans ZENGYM sont ameliores grace a :", "point"=>"1", "id_examen"=>1],
                ["id"=>12, "question"=>"L'entrainement ZENGYM est particulierement adapte pour :", "point"=>"1", "id_examen"=>1],
                ["id"=>13, "question"=>"L'equilibre dynamique est essentiel car :", "point"=>"1", "id_examen"=>1],
                ["id"=>14, "question"=>"Les muscles stabilisateurs travailles dans ZENGYM incluent :", "point"=>"1", "id_examen"=>1],
                ["id"=>15, "question"=>"Le travail excentrique des muscles dans ZENGYM :", "point"=>"1", "id_examen"=>1],
                ["id"=>16, "question"=>"ZENGYM combine quels types de forces ?", "point"=>"1", "id_examen"=>1],
                ["id"=>17, "question"=>"La pratique reguliere de ZENGYM reduit :", "point"=>"1", "id_examen"=>1],
                ["id"=>18, "question"=>"Une des techniques cles enseignees aux instructeurs ZENGYM est :", "point"=>"1", "id_examen"=>1],
                ["id"=>19, "question"=>"Le concept sensoriel de ZENGYM repose sur :", "point"=>"1", "id_examen"=>1],
                ["id"=>20, "question"=>"Pourquoi ZENGYM est-il decrit comme un sport medical ?", "point"=>"1", "id_examen"=>1]
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
