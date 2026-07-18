<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Affiche de l'événement (chemin relatif storage/app/public/evenements/)
            $table->string('affiche')->nullable()->after('approuver');
            // null = en attente, true = accepté, false = refusé
            // On renomme la logique : approuver = null (en attente), true (accepté)
            // On ajoute refuser pour distinguer refus de "pas encore traité"
            $table->boolean('refuser')->nullable()->default(null)->after('affiche');
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropColumn(['affiche', 'refuser']);
        });
    }
};
