<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;
use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\CategTypeAboAdherent;
use App\Models\TypeAboAdherent;
use App\Models\AboAdherent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AboAdherentController extends Controller
{
    public function index_categ_abo(){
        $liste = CategTypeAboAdherent::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_abo(Request $request){
        $max_id = CategTypeAboAdherent::max('id');
        $code = 'CA_00'.$max_id;

        CategTypeAboAdherent::create([
            'code'=>$code,
            'desc'=>$request->desc,
            'photo'=>$request->photo,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_abo($id,Request $request){
        $detail = CategTypeAboAdherent::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_abo($id,Request $request){

        CategTypeAboAdherent::where('id',$id)
            ->update([
                'desc'=>$request->desc,
                'photo'=>$request->photo,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_abo(Request $request){
        $id = $request->champ_id;
        TypeAboAdherent::where('categ_abo_id',$id)
            ->delete();
        CategTypeAboAdherent::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_type_abo(){

        $type_abolist =[];
        $liste = TypeAboAdherent::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($type_abolist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'des'=>$liste[$j]->des,
                        'periode'=>$liste[$j]->periode,
                        'frais'=>$liste[$j]->frais,
                        'remise'=>$liste[$j]->remise,
                        'frais_ap_remise'=>$liste[$j]->frais_ap_remise,
                        'seance_essai'=>$liste[$j]->seance_essai,
                        'frais_seance_essai'=>$liste[$j]->frais_seance_essai,
                        'nbr_pers_limit'=>$liste[$j]->nbr_pers_limit,
                        'categ_abo_desc'=>CategTypeAboAdherent::where('id',$liste[$j]->categ_abo_id)->value('desc'),
                    ]);
            }
        }
        $list_cat = CategTypeAboAdherent::all();
        return response()->json([
            "status" => true,
            'liste' => $type_abolist,
            "message" => '',
            'list_cat' => $list_cat,
        ]);
    }
    public function add_type_abo(Request $request){
        $max_id = TypeAboAdherent::max('id');
        $code = 'TA_00'.$max_id;

        TypeAboAdherent::create([
            'code'=>$code,
            'des'=>$request->des,
            'periode'=>$request->periode,
            'frais'=>$request->frais,
            'remise'=>$request->remise,
            'frais_ap_remise'=>$request->frais_ap_remise,
            'seance_essai'=>$request->seance_essai,
            'frais_seance_essai'=>$request->frais_seance_essai,
            'categ_abo_id'=>$request->categ_abo_id,
            'nbr_pers_limit'=>$request->nbr_pers_limit?? 0,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',

        ]);
    }
    public function edit_type_abo($id,Request $request){
        $detail = TypeAboAdherent::where('id',$id)->get();
        $list_cat = CategTypeAboAdherent::all();
        $desc_cat = CategTypeAboAdherent::where('id',$detail[0]->categ_abo_id)->value('desc');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
        ]);
    }
    public function update_type_abo($id,Request $request){
        TypeAboAdherent::where('id',$id)
            ->update([
                'des'=>$request->des,
                'periode'=>$request->periode,
                'frais'=>$request->frais,
                'remise'=>$request->remise,
                'frais_ap_remise'=>$request->frais_ap_remise,
                'seance_essai'=>$request->seance_essai,
                'frais_seance_essai'=>$request->frais_seance_essai,
                'categ_abo_id'=>$request->categ_abo_id,
                'nbr_pers_limit'=>$request->nbr_pers_limit,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_type_abo(Request $request){
        $id = $request->champ_id;
        AboAdherent::where('type_abo_id',$id)
            ->delete();
        TypeAboAdherent::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_abo(){

        $abolist =[];
        $liste = AboAdherent::orderBy('created_at','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($abolist,
                    [
                        'id'=>$liste[$j]->id,
                        'titre'=>$liste[$j]->titre?? TypeAboAdherent::where('id',$liste[$j]->type_abo_id)->value('des'),
                        'frais'=> TypeAboAdherent::where('id',$liste[$j]->type_abo_id)->value('frais'),
                        'remise'=> TypeAboAdherent::where('id',$liste[$j]->type_abo_id)->value('remise'),
                        'frais_ap_remise'=> TypeAboAdherent::where('id',$liste[$j]->type_abo_id)->value('frais_ap_remise'),
                        'categorie_type'=> CategTypeAboAdherent::where('id',TypeAboAdherent::where('id',$liste[$j]->type_abo_id)->value('categ_abo_id'))->value('desc') ,
                        'date_paie'=>$liste[$j]->date_paie,
                        'ref'=>$liste[$j]->ref,
                        'status_paie'=>$liste[$j]->status_paie,
                        'active'=>$liste[$j]->active,
                        'date_deb'=>$liste[$j]->date_deb,
                        'date_fin'=>$liste[$j]->date_fin,
                        'user'=>User::where('id',$liste[$j]->user_id)->value('nom').' '.User::where('id',$liste[$j]->user_id)->value('prenom'),

                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $abolist,

            "message" => '',
        ]);
    }
    public function payer_candidat(Request $request, $id_type_abo)
    {
        $user = User::where('role', 'CANDIDAT')->where('mail', $request->mail)->first();

        if ($user && AboAdherent::where('user_id', $user->id)->where('active', true)->exists()) {
            return response()->json([
                "status" => false,
                "message" => "Il existe déjà un compte actif avec cet email !",
            ]);
        }

        $typeAbo = TypeAboAdherent::find($id_type_abo);
        if ($typeAbo && $typeAbo->nbr_pers_limit != 0 && $typeAbo->nbr_pers_limit >= 50) {
            return response()->json([
                "status" => false,
                "message" => "Nous sommes désolés, le nombre maximum d’inscriptions pour cette offre a été atteint. Merci de choisir une autre offre !",
            ]);
        }

        $candidat = Candidat::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'tel1' => $request->tel1,
            'tel2' => $request->tel2,
            'email' => $request->mail,
            'adr' => $request->adr,
            'cp' => $request->cp,
            'role' => 'CANDIDAT',
            'mt_affiliation' => $request->mt_affiliation,
            'categ_candidat_id' => $request->categ_candidat_id,
            'salle_sport_id' => $request->salle_sport_id,
            'instructeur_id' => $request->instructeur_id,
            'created_at' => now(),
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'mail' => $request->mail,
            'email' => $request->mail,
            'password' => Hash::make($request->password),
            'candidat_id' => $candidat->id,
            'role' => 'CANDIDAT',
        ]);

        AboAdherent::create([
            'type_paie' => $request->methode_paiement,
            'status_paie' => $request->paiement_status,
            'ref' => $request->ref,
            'type_abo_id' => $id_type_abo,
            'user_id' => $user->id,
            'date_validation' => $request->date_validation,
            'titre' => $request->titre,
        ]);

        if ($request->methode_paiement === 'Konnect+' && $request->paiement_status) {
            $this->transferer_candidat_vers_instructeur($user->id, $id_type_abo);
        }

        return response()->json([
            "status" => true,
            "message" => "Nous vous remercions pour votre inscription. Votre carte est actuellement en cours de traitement",
        ]);
    }

    public function valider_abo_adherent($id_abo){
        $id_abo = $id_abo; // ou ta variable

        $Abo_detail = AboAdherent::find($id_abo);
        $periode = strtolower(trim($Abo_detail->periode)); // ex: "3 mois", "1 mois", "annuel"

        $dateDebut = Carbon::now();
        $dateFin = $dateDebut->copy(); // pour éviter d’altérer la date de début

        if (preg_match('/(\d+)\s*moi/i', $periode, $matches)) {
            // si la période contient un nombre + "moi" (ex: "2 mois", "1 mois")
            $nbMois = (int) $matches[1];
            $dateFin->addMonths($nbMois);
        } elseif (str_contains($periode, 'an')) {
            // si c’est annuel, 1 an ou autre variation
            $dateFin->addYear();
        } else {
            // valeur par défaut : 1 mois
            $dateFin->addMonth();
        }

        AboAdherent::where('id', $id_abo)->update([
            'active' => true,
            'status_paie' => true,
            'date_deb' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
        ]);

        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }

    public function delete_abo(Request $request){
        $id = $request->champ_id;

        AboAdherent::where('id', $id)->update([
            'active' => false,
        ]);

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}