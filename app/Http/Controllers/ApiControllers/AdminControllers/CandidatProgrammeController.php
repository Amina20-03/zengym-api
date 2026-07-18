<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatProgrammeController extends Controller
{
    private function photoUrl(?string $path): ?string
    {
        if (!$path) return null;
        return 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $path;
    }

    /**
     * Programmes assignés à un candidat
     */
    public function index($candidat_id)
    {
        $assigned = DB::table('candidat_programmes')
            ->where('candidat_id', $candidat_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = $assigned->map(function ($row) {
            $prog = Programme::find($row->programme_id);
            if (!$prog) return null;
            return [
                'pivot_id'       => $row->id,
                'programme_id'   => $prog->id,
                'titre'          => $prog->titre,
                'description'    => $prog->description,
                'duree_semaines' => $prog->duree_semaines,
                'niveau'         => $prog->niveau,
                'photo_url'      => $this->photoUrl($prog->photo),
                'date_debut'     => $row->date_debut,
                'statut'         => $row->statut,
            ];
        })->filter()->values();

        return response()->json(['status' => true, 'liste' => $result, 'message' => '']);
    }

    /**
     * Programmes de l'instructeur non encore assignés à ce candidat
     */
    public function available($candidat_id, Request $request)
    {
        $instructeur_id = $request->instructeur_id;

        $assignedIds = DB::table('candidat_programmes')
            ->where('candidat_id', $candidat_id)
            ->pluck('programme_id')
            ->toArray();

        $available = Programme::where('instructeur_id', $instructeur_id)
            ->where('actif', true)
            ->whereNotIn('id', $assignedIds)
            ->get()
            ->map(fn($p) => [
                'id'             => $p->id,
                'titre'          => $p->titre,
                'duree_semaines' => $p->duree_semaines,
                'niveau'         => $p->niveau,
                'photo_url'      => $this->photoUrl($p->photo),
            ]);

        return response()->json(['status' => true, 'liste' => $available, 'message' => '']);
    }

    /**
     * Assigner un programme à un candidat
     */
    public function assign(Request $request, $candidat_id)
    {
        $programme_id = $request->programme_id;

        // Vérifier si déjà assigné
        $exists = DB::table('candidat_programmes')
            ->where('candidat_id', $candidat_id)
            ->where('programme_id', $programme_id)
            ->exists();

        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Programme déjà assigné.'], 409);
        }

        DB::table('candidat_programmes')->insert([
            'candidat_id'  => $candidat_id,
            'programme_id' => $programme_id,
            'date_debut'   => $request->date_debut ?? now()->format('Y-m-d'),
            'statut'       => $request->statut ?? 'en_cours',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Programme assigné.']);
    }

    /**
     * Retirer un programme d'un candidat
     */
    public function unassign($pivot_id)
    {
        DB::table('candidat_programmes')->where('id', $pivot_id)->delete();
        return response()->json(['status' => true, 'message' => 'Programme retiré.']);
    }

    /**
     * Changer le statut d'un programme assigné
     */
    public function updateStatut(Request $request, $pivot_id)
    {
        DB::table('candidat_programmes')
            ->where('id', $pivot_id)
            ->update(['statut' => $request->statut, 'updated_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Statut mis à jour.']);
    }
}
