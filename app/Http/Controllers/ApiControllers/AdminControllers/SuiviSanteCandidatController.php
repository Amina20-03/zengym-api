<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuiviSanteCandidatController extends Controller
{
    private function format($s): array
    {
        return [
            'id'                  => $s->id,
            'candidat_id'         => $s->candidat_id,
            'date_suivi'          => $s->date_suivi,
            'poids'               => $s->poids,
            'taille'              => $s->taille,
            'imc'                 => $s->imc,
            'glycemie'            => $s->glycemie,
            'tension_arterielle'  => $s->tension_arterielle,
            'frequence_cardiaque' => $s->frequence_cardiaque,
            'niveau_stress'       => $s->niveau_stress,
            'objectifs'           => $s->objectifs,
            'progression'         => $s->progression,
            'notes'               => $s->notes,
            'created_at'          => $s->created_at,
        ];
    }

    public function index($candidat_id)
    {
        $liste = DB::table('suivi_sante_candidat')
            ->where('candidat_id', $candidat_id)
            ->orderBy('date_suivi', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'liste'  => $liste->map(fn($s) => $this->format($s)),
        ]);
    }

    public function store(Request $request, $candidat_id)
    {
        // Calculer IMC automatiquement si poids et taille fournis
        $imc = null;
        if ($request->poids && $request->taille && $request->taille > 0) {
            $tailleM = $request->taille / 100;
            $imc     = round($request->poids / ($tailleM * $tailleM), 1);
        }

        $id = DB::table('suivi_sante_candidat')->insertGetId([
            'candidat_id'         => $candidat_id,
            'date_suivi'          => $request->date_suivi,
            'poids'               => $request->poids               ?: null,
            'taille'              => $request->taille              ?: null,
            'imc'                 => $imc,
            'glycemie'            => $request->glycemie            ?: null,
            'tension_arterielle'  => $request->tension_arterielle  ?: null,
            'frequence_cardiaque' => $request->frequence_cardiaque ?: null,
            'niveau_stress'       => $request->niveau_stress       ?: null,
            'objectifs'           => $request->objectifs           ?: null,
            'progression'         => $request->progression         ?: null,
            'notes'               => $request->notes               ?: null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return response()->json(['status' => true, 'id' => $id, 'imc' => $imc, 'message' => 'Suivi enregistré.']);
    }

    public function show($id)
    {
        $s = DB::table('suivi_sante_candidat')->where('id', $id)->first();
        if (!$s) return response()->json(['status' => false, 'message' => 'Introuvable.'], 404);
        return response()->json(['status' => true, 'detail' => $this->format($s)]);
    }

    public function update(Request $request, $id)
    {
        $s = DB::table('suivi_sante_candidat')->where('id', $id)->first();
        if (!$s) return response()->json(['status' => false, 'message' => 'Introuvable.'], 404);

        $poids  = $request->poids  ?: $s->poids;
        $taille = $request->taille ?: $s->taille;
        $imc    = null;
        if ($poids && $taille && $taille > 0) {
            $tailleM = $taille / 100;
            $imc     = round($poids / ($tailleM * $tailleM), 1);
        }

        DB::table('suivi_sante_candidat')->where('id', $id)->update([
            'date_suivi'          => $request->date_suivi          ?? $s->date_suivi,
            'poids'               => $request->poids               ?: $s->poids,
            'taille'              => $request->taille              ?: $s->taille,
            'imc'                 => $imc ?? $s->imc,
            'glycemie'            => $request->glycemie            ?: $s->glycemie,
            'tension_arterielle'  => $request->tension_arterielle  ?: $s->tension_arterielle,
            'frequence_cardiaque' => $request->frequence_cardiaque ?: $s->frequence_cardiaque,
            'niveau_stress'       => $request->niveau_stress       ?: $s->niveau_stress,
            'objectifs'           => $request->objectifs           ?? $s->objectifs,
            'progression'         => $request->progression         ?? $s->progression,
            'notes'               => $request->notes               ?? $s->notes,
            'updated_at'          => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Suivi mis à jour.']);
    }

    public function destroy($id)
    {
        DB::table('suivi_sante_candidat')->where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Suivi supprimé.']);
    }
}
