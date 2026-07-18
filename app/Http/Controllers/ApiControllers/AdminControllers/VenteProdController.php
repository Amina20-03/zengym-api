<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\CategCandidat;
use App\Models\CategProduit;
use App\Models\Compte;
use App\Models\Instructeur;
use App\Models\LigneVenteProd;
use App\Models\Operation;
use App\Models\Pourcentage;
use App\Models\Produit;
use App\Models\SalleDeSport;
use App\Models\User;
use App\Models\VenteProd;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VenteProdController extends Controller
{
    public function index_vente(){
        $currentDate = Carbon::now();
        $du_date = date('Y-m-01');
        $au_date = $currentDate->endOfMonth()->format('Y-m-d');

        $liste = VenteProd::where('date','>=',$du_date)
            ->where('date','<=',$au_date)
            ->orderBy('date','desc')
            ->get();

        // Charger tous les instructeurs en une seule requête
        $instructeur_ids = $liste->pluck('instructeur_id')->unique()->filter();
        $instructeurs = User::whereIn('instructeur_id', $instructeur_ids)
            ->get(['instructeur_id','nom','prenom'])
            ->keyBy('instructeur_id');

        $vente_list = $liste->map(function($v) use ($instructeurs) {
            return [
                'id'              => $v->id,
                'code'            => $v->code,
                'date'            => $v->date,
                'tot_ht'          => $v->tot_ht,
                'tot_ttc'         => $v->tot_ttc,
                'paiement_par'    => $v->paiement_par,
                'paiement_status' => $v->paiement_status,
                'encaisse'        => $v->encaisse,
                'instructeur_id'  => $v->instructeur_id,
                'instructeur'     => isset($instructeurs[$v->instructeur_id])
                    ? $instructeurs[$v->instructeur_id]->nom.' '.$instructeurs[$v->instructeur_id]->prenom
                    : '',
            ];
        })->values()->all();

        // Charger uniquement les lignes des ventes du mois filtrées
        $vente_ids = $liste->pluck('id');
        $liste2 = LigneVenteProd::whereIn('vente_prod_id', $vente_ids)
            ->orderBy('id','desc')
            ->get();

        // Charger tous les produits en une seule requête
        $prod_ids = $liste2->pluck('prod_id')->unique()->filter();
        $produits = Produit::whereIn('id', $prod_ids)
            ->get(['id','code','desc','taux_tva','photo'])
            ->keyBy('id');

        $ligne_vente_list = $liste2->map(function($l) use ($produits) {
            return [
                'id'              => $l->id,
                'qte'             => $l->qte,
                'pu_vente'        => $l->pu_vente,
                'pu_net_ht_vente' => $l->pu_net_ht_vente,
                'remise'          => $l->remise,
                'prod_id'         => $l->prod_id,
                'vente_prod_id'   => $l->vente_prod_id,
                'prod_code'       => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->code     : '',
                'prod_desc'       => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->desc     : '',
                'prod_taux_tva'   => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->taux_tva : '',
                'prod_photo'      => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->photo    : '',
            ];
        })->values()->all();

        return response()->json([
            "status"           => true,
            'liste'            => $vente_list,
            "message"          => '',
            'ligne_vente_list' => $ligne_vente_list,
            'du_date'          => $du_date,
            'au_date'          => $au_date,
        ]);
    }

    public function search_vente(Request $request){
        $du_date = $request->du_date;
        $au_date = $request->au_date;
        $currentDate = Carbon::now();

        $liste = VenteProd::where('date','>=',$du_date)
            ->where('date','<=',$au_date)
            ->orderBy('date','desc')
            ->get();

        // Charger tous les instructeurs en une seule requête
        $instructeur_ids = $liste->pluck('instructeur_id')->unique()->filter();
        $instructeurs = User::whereIn('instructeur_id', $instructeur_ids)
            ->get(['instructeur_id','nom','prenom'])
            ->keyBy('instructeur_id');

        $vente_list = $liste->map(function($v) use ($instructeurs) {
            return [
                'id'              => $v->id,
                'code'            => $v->code,
                'date'            => $v->date,
                'tot_ht'          => $v->tot_ht,
                'tot_ttc'         => $v->tot_ttc,
                'encaisse'        => $v->encaisse,
                'instructeur_id'  => $v->instructeur_id,
                'instructeur'     => isset($instructeurs[$v->instructeur_id])
                    ? $instructeurs[$v->instructeur_id]->nom.' '.$instructeurs[$v->instructeur_id]->prenom
                    : '',
            ];
        })->values()->all();

        // Charger uniquement les lignes des ventes filtrées par période
        $vente_ids = $liste->pluck('id');
        $liste2 = LigneVenteProd::whereIn('vente_prod_id', $vente_ids)
            ->orderBy('id','desc')
            ->get();

        // Charger tous les produits en une seule requête
        $prod_ids = $liste2->pluck('prod_id')->unique()->filter();
        $produits = Produit::whereIn('id', $prod_ids)
            ->get(['id','code','desc','taux_tva','photo'])
            ->keyBy('id');

        $ligne_vente_list = $liste2->map(function($l) use ($produits) {
            return [
                'id'              => $l->id,
                'qte'             => $l->qte,
                'pu_vente'        => $l->pu_vente,
                'pu_net_ht_vente' => $l->pu_net_ht_vente,
                'remise'          => $l->remise,
                'prod_id'         => $l->prod_id,
                'vente_prod_id'   => $l->vente_prod_id,
                'prod_code'       => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->code     : '',
                'prod_desc'       => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->desc     : '',
                'prod_taux_tva'   => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->taux_tva : '',
                'prod_photo'      => isset($produits[$l->prod_id]) ? $produits[$l->prod_id]->photo    : '',
            ];
        })->values()->all();

        return response()->json([
            "status"           => true,
            'liste'            => $vente_list,
            "message"          => '',
            'ligne_vente_list' => $ligne_vente_list,
            'du_date'          => $du_date,
            'au_date'          => $au_date,
        ]);
    }

    public function create_vente(){
        $list_cat_prod = CategProduit::all();
        $list_prod = Produit::where('active',true)->get();
        $list_instructeurs = User::where('instructeur_id','!=',null)->get();
        return response()->json([
            "status" => true,
            'list_cat_prod' => $list_cat_prod,
            "message" => '',
            'list_prod' => $list_prod,
            'list_instructeurs' => $list_instructeurs,
        ]);
    }

    public function add_vente(Request $request){
        $max_id = VenteProd::max('id');
        $code = 'VP_00'.$max_id;
        VenteProd::create([
            'code'=>$code,
            'date'=>$request->date,
            'tot_ht'=>$request->tot_ht,
            'tot_ttc'=>$request->tot_ttc,
            'instructeur_id'=>$request->instructeur_id,
            'paiement_status' => $request->paiement_status,
            'paiement_par' => $request->paiement_par,
            'encaisse'=>false,
            'created_at' => now(),
        ]);
        $qte_list = explode("|", $request->qte_list);
        $pu_vente_list = explode("|", $request->pu_vente_list);
        $pu_net_ht_vente_list = explode("|", $request->pu_net_ht_vente_list);
        $remise_list = explode("|", $request->remise_list);
        $prod_id_list = explode("|", $request->prod_id_list);
        if ($qte_list != null){
            if(count($qte_list)>0){
                for ($i=0;$i<count($qte_list);$i++){
                    if ($qte_list[$i] != "") {
                        LigneVenteProd::create([
                            'qte'=>$qte_list[$i],
                            'pu_vente'=>$pu_vente_list[$i],
                            'pu_net_ht_vente'=>$pu_net_ht_vente_list[$i],
                            'remise'=>$remise_list[$i],
                            'prod_id'=>$prod_id_list[$i],
                            'vente_prod_id'=>VenteProd::max('id'),
                            'created_at' => now(),
                        ]);
                    }
                }
            }
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function encaisse_vente($id,Request $request){
        $vente_detail = VenteProd::where('id',$id)->get();
        $instructeur_detail = Instructeur::where('id',$vente_detail[0]->instructeur_id)->get() ;
        $compte_detail = Compte::where('instructeur_id',$vente_detail[0]->instructeur_id)->get() ;
        $pourcentage_detail = Pourcentage::where('cat_inst_id',$instructeur_detail[0]->categ_instructeur_id)->get() ;
        $tot_ttc = $vente_detail[0]->tot_ttc;
        $amount_to_remove = ($pourcentage_detail[0]->pr_prod / 100) * $tot_ttc;

        $max_id = Operation::max('id');
        $code = 'OP_PR_00'.$max_id+1;
        Operation::create([
            'code'=>$code,
            'date'=>now(),
            'montant'=>$amount_to_remove,
            'compte_id'=>$compte_detail[0]->id,
            'type'=>'Crédit',
        ]);
        Compte::where('id',$compte_detail[0]->id)
            ->update([
                'soldecpte'=>floatval($request->soldecpte)+floatval($amount_to_remove),
                'datedernmodif'=>now(),
            ]);
        VenteProd::where('id',$id)
            ->update([
                'encaisse'=>true,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function delete_vente(Request $request){
        $id = $request->champ_id;
        LigneVenteProd::where('vente_prod_id',$id)->delete();
        VenteProd::where('id',$id)->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function delete_ligne_vente(Request $request){
        $id = $request->champ_id;
        LigneVenteProd::where('id',$id)->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function edit_vente($id,Request $request){
        $detail = VenteProd::where('id',$id)->get();
        $list_cat_prod = CategProduit::all();
        $list_prod = Produit::where('active',true)->get();
        $list_instructeurs = User::where('instructeur_id','!=',null)->get();
        $instructeur = User::where('instructeur_id',$detail[0]->instructeur_id)
            ->selectRaw("CONCAT(nom,' ',prenom) as full_name")
            ->value('full_name');

        return response()->json([
            "status" => true,
            "detail" => $detail,
            'list_cat_prod' => $list_cat_prod,
            "message" => '',
            'list_prod' => $list_prod,
            'list_instructeurs' => $list_instructeurs,
            "instructeur" => $instructeur,
        ]);
    }
}
