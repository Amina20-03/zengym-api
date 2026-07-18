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
        Schema::create('choix_quests', function (Blueprint $table) {
            $table->id();
            $table->string('rep')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('id_question')->nullable();
            $table->timestamps();
        });
        DB::table('choix_quests')->insert([
            [
                "id"=>1,
                "rep"=>"Les quadriceps",
                "status"=>false,
                "id_question"=>1,
            ],
            [
                "id"=>2,
                "rep"=>"Le transverse de l'abdomen",
                "status"=>true,
                "id_question"=>1,
            ],
            [
                "id"=>3,
                "rep"=>"Les biceps brachiaux",
                "status"=>false,
                "id_question"=>1,
            ],
            [
                "id"=>4,
                "rep"=>"Les trapezes",
                "status"=>false,
                "id_question"=>1,
            ],
            [
                "id"=>5,
                "rep"=>"Anaerobie lactique",
                "status"=>false,
                "id_question"=>2,
            ],
            [
                "id"=>6,
                "rep"=>"Aerobie",
                "status"=>true,
                "id_question"=>2,
            ],
            [
                "id"=>7,
                "rep"=>"Anaerobie alactique",
                "status"=>false,
                "id_question"=>2,
            ],
            [
                "id"=>8,
                "rep"=>"ATP-phosphocreatine",
                "status"=>false,
                "id_question"=>2,
            ],
            [
                "id"=>9,
                "rep"=>"Augmenter le stress",
                "status"=>false,
                "id_question"=>3,
            ],
            [
                "id"=>10,
                "rep"=>"Reduire l'anxiete",
                "status"=>true,
                "id_question"=>3,
            ],
            [
                "id"=>11,
                "rep"=>"Provoquer une fatigue mentale",
                "status"=>false,
                "id_question"=>3,
            ],
            [
                "id"=>12,
                "rep"=>"Developper l'agressivite",
                "status"=>false,
                "id_question"=>3,
            ],
            [
                "id"=>13,
                "rep"=>"La musculature superficielle uniquement",
                "status"=>false,
                "id_question"=>4,
            ],
            [
                "id"=>14,
                "rep"=>"La circulation sanguine et l'oxygenation",
                "status"=>true,
                "id_question"=>4,
            ],
            [
                "id"=>15,
                "rep"=>"Les os des membres superieurs",
                "status"=>false,
                "id_question"=>4,
            ],
            [
                "id"=>16,
                "rep"=>"Les fibres musculaires rapides exclusivement",
                "status"=>false,
                "id_question"=>4,
            ],
            [
                "id"=>17,
                "rep"=>"10 minutes",
                "status"=>false,
                "id_question"=>5,
            ],
            [
                "id"=>18,
                "rep"=>"30 minutes",
                "status"=>true,
                "id_question"=>5,
            ],
            [
                "id"=>19,
                "rep"=>"45 minutes",
                "status"=>false,
                "id_question"=>5,
            ],
            [
                "id"=>20,
                "rep"=>"60 minutes",
                "status"=>false,
                "id_question"=>5,
            ],
            [
                "id"=>21,
                "rep"=>"Rapidement mais pour une courte duree",
                "status"=>false,
                "id_question"=>6,
            ],
            [
                "id"=>22,
                "rep"=>"Lentement mais pour une longue duree",
                "status"=>true,
                "id_question"=>6,
            ],
            [
                "id"=>23,
                "rep"=>"Sans utiliser d'oxygene",
                "status"=>false,
                "id_question"=>6,
            ],
            [
                "id"=>24,
                "rep"=>"Avec production d'acide lactique",
                "status"=>false,
                "id_question"=>6,
            ],
            [
                "id"=>25,
                "rep"=>"Renforcement musculaire et amelioration de la posture",
                "status"=>false,
                "id_question"=>7,
            ],
            [
                "id"=>26,
                "rep"=>"Perte de masse musculaire",
                "status"=>true,
                "id_question"=>7,
            ],
            [
                "id"=>27,
                "rep"=>"Augmentation de la fatigue",
                "status"=>false,
                "id_question"=>7,
            ],
            [
                "id"=>28,
                "rep"=>"Stimulation des muscles superficiels uniquement",
                "status"=>false,
                "id_question"=>7,
            ],
            [
                "id"=>29,
                "rep"=>"L'adrenaline",
                "status"=>false,
                "id_question"=>8,
            ],
            [
                "id"=>30,
                "rep"=>"La dopamine",
                "status"=>true,
                "id_question"=>8,
            ],
            [
                "id"=>31,
                "rep"=>"Le cortisol",
                "status"=>false,
                "id_question"=>8,
            ],
            [
                "id"=>32,
                "rep"=>"La thyroxine",
                "status"=>false,
                "id_question"=>8,
            ],
            [
                "id"=>33,
                "rep"=>"Necessitent des equipements lourds",
                "status"=>false,
                "id_question"=>9,
            ],
            [
                "id"=>34,
                "rep"=>"Sont inutilises",
                "status"=>false,
                "id_question"=>9,
            ],
            [
                "id"=>35,
                "rep"=>"Aident a renforcer les muscles profonds",
                "status"=>true,
                "id_question"=>9,
            ],
            [
                "id"=>36,
                "rep"=>"Causent des blessures frequentes",
                "status"=>false,
                "id_question"=>9,
            ],
            [
                "id"=>37,
                "rep"=>"Est choisie au hasard",
                "status"=>false,
                "id_question"=>10,
            ],
            [
                "id"=>38,
                "rep"=>"Possede un tempo therapeutique",
                "status"=>true,
                "id_question"=>10,
            ],
            [
                "id"=>39,
                "rep"=>"Sert uniquement a divertir",
                "status"=>false,
                "id_question"=>10,
            ],
            [
                "id"=>40,
                "rep"=>"Augmente l'intensite de l'effort",
                "status"=>false,
                "id_question"=>10,
            ],
            [
                "id"=>41,
                "rep"=>"Des poids et halteres",
                "status"=>false,
                "id_question"=>11,
            ],
            [
                "id"=>42,
                "rep"=>"La relaxation et les mouvements doux",
                "status"=>true,
                "id_question"=>11,
            ],
            [
                "id"=>43,
                "rep"=>"La vitesse d'execution",
                "status"=>false,
                "id_question"=>11,
            ],
            [
                "id"=>44,
                "rep"=>"L'augmentation de la charge",
                "status"=>false,
                "id_question"=>11,
            ],
            [
                "id"=>45,
                "rep"=>"Les athletes de haut niveau uniquement",
                "status"=>false,
                "id_question"=>12,
            ],
            [
                "id"=>46,
                "rep"=>"Les personnes sedentaires ou en reeducation",
                "status"=>true,
                "id_question"=>12,
            ],
            [
                "id"=>47,
                "rep"=>"Les enfants de moins de 5 ans",
                "status"=>false,
                "id_question"=>12,
            ],
            [
                "id"=>48,
                "rep"=>"Les culturistes exclusivement",
                "status"=>false,
                "id_question"=>12,
            ],
            [
                "id"=>49,
                "rep"=>"Il reduit le stress mental",
                "status"=>false,
                "id_question"=>13,
            ],
            [
                "id"=>50,
                "rep"=>"Il ameliore la coordination en mouvement",
                "status"=>true,
                "id_question"=>13,
            ],
            [
                "id"=>51,
                "rep"=>"Il supprime la fatigue musculaire",
                "status"=>false,
                "id_question"=>13,
            ],
            [
                "id"=>52,
                "rep"=>"Il ralentit la circulation sanguine",
                "status"=>false,
                "id_question"=>13,
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choix_quests');
    }
};
