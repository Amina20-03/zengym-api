<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\SuiviSante;
use Illuminate\Http\Request;

class SuiviSanteController extends Controller
{
    public function rapport($candidat_id)
    {
        $suivis = \App\Models\SuiviSante::where('candidat_id', $candidat_id)
            ->orderBy('date_suivi', 'asc')
            ->get();

        if ($suivis->isEmpty()) {
            return response()->json(['status' => true, 'rapport' => null, 'suivis' => []]);
        }

        $premier = $suivis->first();
        $dernier = $suivis->last();

        // Calculs
        $poids_initial = $premier->poids;
        $poids_actuel  = $dernier->poids;
        $perte_poids   = $poids_initial && $poids_actuel ? round($poids_actuel - $poids_initial, 1) : null;

        $presences = $suivis->filter(fn($s) => $s->presence === 'Présent')->count();
        $total     = $suivis->count();
        $taux_presence = $total > 0 ? round(($presences / $total) * 100) : 0;

        $tension_moy = $suivis->whereNotNull('tension_arterielle')->pluck('tension_arterielle')->last();
        $energie_moy = $suivis->whereNotNull('niveau_energie')->avg('niveau_energie');
        $stress_moy  = $suivis->whereNotNull('niveau_stress')->avg('niveau_stress');

        // Données pour graphique poids — filtre strict sur les valeurs numériques
        $poids_evolution = $suivis->filter(fn($s) => $s->poids !== null && $s->poids > 0)
            ->map(fn($s) => [
                'date'  => $s->date_suivi,
                'poids' => (float)$s->poids,
            ])->values();

        return response()->json([
            'status' => true,
            'rapport' => [
                'periode_debut'   => $premier->date_suivi,
                'periode_fin'     => $dernier->date_suivi,
                'poids_initial'   => $poids_initial,
                'poids_actuel'    => $poids_actuel,
                'perte_poids'     => $perte_poids,
                'taux_presence'   => $taux_presence,
                'tension_moy'     => $tension_moy,
                'energie_moy'     => $energie_moy ? round($energie_moy, 1) : null,
                'stress_moy'      => $stress_moy  ? round($stress_moy, 1)  : null,
                'nb_seances'      => $total,
            ],
            'poids_evolution' => $poids_evolution,
            'suivis'          => $suivis,
        ]);
    }

    public function index($candidat_id)
    {
        $liste = SuiviSante::where('candidat_id', $candidat_id)
            ->orderBy('date_suivi', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'liste'   => $liste,
            'message' => '',
        ]);
    }

    public function store(Request $request, $candidat_id)
    {
        $suivi = SuiviSante::create([
            'candidat_id'         => $candidat_id,
            'date_suivi'          => $request->date_suivi,
            'poids'               => $request->poids               ?: null,
            'tour_de_taille'      => $request->tour_de_taille      ?: null,
            'tension_arterielle'  => $request->tension_arterielle  ?: null,
            'frequence_cardiaque' => $request->frequence_cardiaque ?: null,
            'glycemie'            => $request->glycemie            ?: null,
            'saturation_oxygene'  => $request->saturation_oxygene  ?: null,
            'niveau_energie'      => $request->niveau_energie      ?: null,
            'niveau_stress'       => $request->niveau_stress       ?: null,
            'qualite_sommeil'     => $request->qualite_sommeil     ?: null,
            'presence'            => $request->presence            ?: null,
            'commentaire'         => $request->commentaire         ?: null,
        ]);

        return response()->json([
            'status'  => true,
            'suivi'   => $suivi,
            'message' => 'Suivi enregistré.',
        ]);
    }

    public function show($id)
    {
        $suivi = SuiviSante::findOrFail($id);
        return response()->json([
            'status'  => true,
            'detail'  => $suivi,
            'message' => '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $suivi = SuiviSante::findOrFail($id);
        $suivi->update([
            'date_suivi'          => $request->date_suivi          ?? $suivi->date_suivi,
            'poids'               => $request->poids               ?: $suivi->poids,
            'tour_de_taille'      => $request->tour_de_taille      ?: $suivi->tour_de_taille,
            'tension_arterielle'  => $request->tension_arterielle  ?: $suivi->tension_arterielle,
            'frequence_cardiaque' => $request->frequence_cardiaque ?: $suivi->frequence_cardiaque,
            'glycemie'            => $request->glycemie            ?: $suivi->glycemie,
            'saturation_oxygene'  => $request->saturation_oxygene  ?: $suivi->saturation_oxygene,
            'niveau_energie'      => $request->niveau_energie      ?: $suivi->niveau_energie,
            'niveau_stress'       => $request->niveau_stress       ?: $suivi->niveau_stress,
            'qualite_sommeil'     => $request->qualite_sommeil     ?: $suivi->qualite_sommeil,
            'presence'            => $request->presence            ?: $suivi->presence,
            'commentaire'         => $request->commentaire         ?? $suivi->commentaire,
        ]);

        return response()->json(['status' => true, 'message' => 'Suivi mis à jour.']);
    }

    public function destroy($id)
    {
        SuiviSante::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Suivi supprimé.']);
    }
}
