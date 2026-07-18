<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CategAbo;
use App\Models\CategInstructeur;
use App\Models\Instructeur;
use App\Models\Pays;
use App\Models\TypeAbo;
use App\Models\User;
use App\Models\VenteAbo;
use App\Models\AbonnementUser;
use Illuminate\Http\Request;

class VenteAboController extends Controller
{
    public function index_categ_abo(){
        $liste = CategAbo::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_abo(Request $request){
        $max_id = CategAbo::max('id');
        $code = 'CA_00'.$max_id;
        CategAbo::create([
            'code'=>$code,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_abo($id,Request $request){
        $detail = CategAbo::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_abo($id,Request $request){
        CategAbo::where('id',$id)
            ->update([
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_abo(Request $request){
        $id = $request->champ_id;
        TypeAbo::where('categ_abo_id',$id)
            ->delete();
        CategAbo::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_type_abo(){

        $type_abolist =[];
        $liste = TypeAbo::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($type_abolist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'nb_mois'=>$liste[$j]->nb_mois,
                        'prix_ttc'=>$liste[$j]->prix_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'prix_ht'=>$liste[$j]->prix_ht,
                        'categ_abo_desc'=>CategAbo::where('id',$liste[$j]->categ_abo_id)->value('desc'),
                    ]);
            }
        }
        $list_cat = CategAbo::all();
        return response()->json([
            "status" => true,
            'liste' => $type_abolist,
            "message" => '',
            'list_cat' => $list_cat,
        ]);
    }
    public function add_type_abo(Request $request){
        $max_id = TypeAbo::max('id');
        $code = 'TA_00'.$max_id;

        TypeAbo::create([
            'code'=>$code,
            'desc'=>$request->desc,
            'nb_mois'=>$request->nb_mois,
            'prix_ttc'=>$request->prix_ttc,
            'taux_tva'=>$request->taux_tva,
            'prix_ht'=>$request->prix_ht,
            'categ_abo_id'=>$request->categ_abo_id,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',

        ]);
    }
    public function edit_type_abo($id,Request $request){
        $detail = TypeAbo::where('id',$id)->get();
        $list_cat = CategAbo::all();
        $desc_cat = CategAbo::where('id',$detail[0]->categ_abo_id)->value('desc');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
        ]);
    }

    public function update_type_abo($id,Request $request){
        TypeAbo::where('id',$id)
            ->update([
                'desc'=>$request->desc,
                'nb_mois'=>$request->nb_mois,
                'prix_ttc'=>$request->prix_ttc,
                'taux_tva'=>$request->taux_tva,
                'prix_ht'=>$request->prix_ht,
                'categ_abo_id'=>$request->categ_abo_id,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_type_abo(Request $request){
        $id = $request->champ_id;
        VenteAbo::where('type_abo_id',$id)
            ->delete();
        TypeAbo::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_abo(){

        $abolist =[];
        $liste = VenteAbo::orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($abolist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'date'=>$liste[$j]->date,
                        'montant_ht'=>$liste[$j]->montant_ht,
                        'montant_ttc'=>$liste[$j]->montant_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'paiement'=>$liste[$j]->paiement,
                        'solder'=>$liste[$j]->solder,
                        'dernier'=>$liste[$j]->dernier,
                        'date_deb'=>$liste[$j]->date_deb,
                        'date_fin'=>$liste[$j]->date_fin,
                        'type_abo_desc'=>TypeAbo::where('id',$liste[$j]->type_abo_id)->value('desc'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom')." ".User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $abolist,

            "message" => '',
        ]);
    }
    public function create_abo(){

        $list_type_abo = TypeAbo::all();
        $list_instructeurs = User::where('instructeur_id','!=',null)->orderby('id','desc')->get();
        $list_pays = Pays::all();
        $list_cat = CategInstructeur::all();
        return response()->json([
            "status" => true,
            'list_type_abo' => $list_type_abo,
            "message" => '',
            'list_instructeurs' => $list_instructeurs,
            'list_pays' => $list_pays,
            'list_cat' => $list_cat,
        ]);
    }
    public function delete_abo(Request $request){
        $id = $request->champ_id;

        AbonnementUser::where('vente_abo_id',$id)
            ->delete();
        VenteAbo::where('id',$id)
            ->delete();

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_abo(Request $request){
        $max_id = VenteAbo::max('id');
        $code = 'VA_00'.$max_id;
        VenteAbo::where('instructeur_id',$request->instructeur_id)
            ->update([
                'dernier'=>false,
            ]);
        $vente_abo = VenteAbo::create([
            'code'=>$code,
            'date'=>$request->date,
            'montant_ht'=>$request->montant_ht,
            'montant_ttc'=>$request->montant_ttc,
            'taux_tva'=>$request->taux_tva,
            'paiement'=>$request->paiement,
            'solder'=>$request->solder,
            'date_deb'=>$request->date_deb,
            'date_fin'=>$request->date_fin,
            'type_abo_id'=>$request->type_abo_id,
            'instructeur_id'=>$request->instructeur_id,
            'dernier'=>true,
            'created_at' => now(),
        ]);
        $detail_user = User::where('instructeur_id',$request->instructeur_id)->get();
        $detail_type_abo = TypeAbo::where('id',$request->type_abo_id)->get();
        AbonnementUser::where('user_id',$detail_user[0]->id)
            ->update([
                'active'=>false,
            ]);
        AbonnementUser::create([
            'titre'=>$detail_type_abo[0]->desc,
            'date_paie'=>$request->date,
            'status_paie'=>1,
            'type_paie'=>'En espèces',
            'type_abo_id'=>$request->type_abo_id,
            'date_deb'=>$request->date_deb,
            'date_fin'=>date($request->date_fin),
            'active'=>true,
            'user_id'=>$detail_user[0]->id,
            'vente_abo_id'=>$vente_abo->id,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
            "instructeur_id" => $request->instructeur_id,
        ]);
    }
}
