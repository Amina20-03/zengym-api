<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RendezVousController extends Controller
{
    private function format($rdv): array
    {
        $candidat   = DB::table('candidats')->where('id', $rdv->candidat_id)->first();
        $nom        = $candidat ? ($candidat->nom . ' ' . $candidat->prenom) : 'Inconnu';
        $photo      = $candidat?->photo
            ? 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $candidat->photo
            : null;

        return [
            'id'             => $rdv->id,
            'candidat_id'    => $rdv->candidat_id,
            'candidat_nom'   => $nom,
            'candidat_photo' => $photo,
            'instructeur_id' => $rdv->instructeur_id,
            'date'           => $rdv->date,
            'heure_deb'      => $rdv->heure_deb,
            'heure_fin'      => $rdv->heure_fin,
            'type'           => $rdv->type,
            'note'           => $rdv->note,
            'statut'         => $rdv->statut,
            'motif_refus'    => $rdv->motif_refus,
            'created_at'     => $rdv->created_at,
        ];
    }

    // Liste des RDV d'un instructeur
    public function index(Request $request)
    {
        $instructeur_id = $request->instructeur_id;
        $rdvs = DB::table('rendez_vous')
            ->where('instructeur_id', $instructeur_id)
            ->orderBy('date')->orderBy('heure_deb')
            ->get();

        return response()->json([
            'status' => true,
            'liste'  => $rdvs->map(fn($r) => $this->format($r)),
        ]);
    }

    // Candidat crée un RDV
    public function store(Request $request)
    {
        // Vérification conflit — RDV acceptés sur le même instructeur
        $conflit = DB::table('rendez_vous')
            ->where('instructeur_id', $request->instructeur_id)
            ->where('date', $request->date)
            ->where('statut', 'accepte')
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('heure_deb', '<=', $request->heure_deb)
                       ->where('heure_fin', '>', $request->heure_deb);
                })->orWhere(function ($q2) use ($request) {
                    $q2->where('heure_deb', '<', $request->heure_fin)
                       ->where('heure_fin', '>=', $request->heure_fin);
                })->orWhere(function ($q2) use ($request) {
                    $q2->where('heure_deb', '>=', $request->heure_deb)
                       ->where('heure_fin', '<=', $request->heure_fin);
                });
            })
            ->exists();

        if ($conflit) {
            return response()->json([
                'status'  => false,
                'message' => 'Un rendez-vous est déjà confirmé sur cette plage horaire. Veuillez choisir un autre créneau.',
            ], 409);
        }

        $id = DB::table('rendez_vous')->insertGetId([
            'candidat_id'    => $request->candidat_id,
            'instructeur_id' => $request->instructeur_id,
            'date'           => $request->date,
            'heure_deb'      => $request->heure_deb,
            'heure_fin'      => $request->heure_fin,
            'type'           => $request->type ?? 'Suivi',
            'note'           => $request->note,
            'statut'         => 'en_attente',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json(['status' => true, 'id' => $id, 'message' => 'Rendez-vous demandé.']);
    }

    // Instructeur accepte
    public function accepter($id)
    {
        DB::table('rendez_vous')->where('id', $id)->update([
            'statut'     => 'accepte',
            'updated_at' => now(),
        ]);
        return response()->json(['status' => true, 'message' => 'Rendez-vous accepté.']);
    }

    // Instructeur refuse
    public function refuser(Request $request, $id)
    {
        DB::table('rendez_vous')->where('id', $id)->update([
            'statut'      => 'refuse',
            'motif_refus' => $request->motif_refus,
            'updated_at'  => now(),
        ]);
        return response()->json(['status' => true, 'message' => 'Rendez-vous refusé.']);
    }

    // Supprimer
    public function destroy($id)
    {
        DB::table('rendez_vous')->where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Rendez-vous supprimé.']);
    }

    // Liste RDV candidat (espace candidat)
    public function mySrdv(Request $request)
    {
        $candidat_id = $request->candidat_id;
        $rdvs = DB::table('rendez_vous')
            ->where('candidat_id', $candidat_id)
            ->orderBy('date')->orderBy('heure_deb')
            ->get();

        return response()->json([
            'status' => true,
            'liste'  => $rdvs->map(fn($r) => $this->format($r)),
        ]);
    }
}
