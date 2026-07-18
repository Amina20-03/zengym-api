<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\BonEntree;
use App\Models\BonSortie;
use App\Models\CategProduit;
use App\Models\Fournisseur;
use App\Models\LigneBonEntree;
use App\Models\LigneBonSortie;
use App\Models\Produit;
use App\Models\StockProduit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockVenteController extends Controller
{
    public function index_fournisseur(){
        $liste = Fournisseur::orderby('id','desc')->get();
        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);

    }
    public function edit_fournisseur($id){
        $detail = Fournisseur::where('id',$id)->get();
        return response()->json([
            "status" => true,
            'detail' => $detail,
            "message" => '',
        ]);
    }
    public function update_fournisseur($id,Request $request){
        Fournisseur::where('id',$id)
            ->update([
                'raison_soc_fourn'=>$request->raison_soc_fourn,
                'contact_fourn'=>$request->contact_fourn,
                'tel1_fourn'=>$request->tel1_fourn,
                'tel2_fourn'=>$request->tel2_fourn,
                'mf_fourn'=>$request->mf_fourn,
                'rc_fourn'=>$request->rc_fourn,
                'updated_at' => now(),

            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_fournisseur(Request $request){

        Fournisseur::create([
            'raison_soc_fourn'=>$request->raison_soc_fourn,
            'contact_fourn'=>$request->contact_fourn,
            'tel1_fourn'=>$request->tel1_fourn,
            'tel2_fourn'=>$request->tel2_fourn,
            'mf_fourn'=>$request->mf_fourn,
            'rc_fourn'=>$request->rc_fourn,
            'created_at' => now(),
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_fournisseur(Request $request){
        $id = $request->champ_id;
        BonEntree::where('fournisseur_id',$id)
            ->delete();
        Fournisseur::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_stock_prod(){

        $stock_prodlist =[];
        $liste = StockProduit::all();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($stock_prodlist,
                    [
                        'id'=>$liste[$j]->id,
                        'qte_stk'=>$liste[$j]->qte_stk,
                        'des_prod'=>Produit::where('id',$liste[$j]->prod_id)->value('desc'),

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $stock_prodlist,
            "message" => '',
        ]);

    }
    public function index_bon_entree(){
        $currentDate = Carbon::now();

        $bon_entreelist =[];
        $liste = BonEntree::where('date_be','>=',date('Y-m-01'))
            ->where('date_be','<=',$currentDate->endOfMonth()->format('Y-m-d'))
            ->orderBy('date_be','desc')
            ->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($bon_entreelist,
                    [
                        'IDBENTREE'=>$liste[$j]->id,
                        'code_be'=>$liste[$j]->code_be,
                        'date_be'=>$liste[$j]->date_be,
                        'totah_ht_be'=>$liste[$j]->totah_ht_be,
                        'total_ttc_be'=>$liste[$j]->total_ttc_be,
                        'raison_soc_fourn'=>Fournisseur::where('id',$liste[$j]->fournisseur_id)->value('raison_soc_fourn'),
                        'contact_fourn'=>Fournisseur::where('id',$liste[$j]->fournisseur_id)->value('contact_fourn'),

                    ]);
            }
        }


        $ligne_belist =[];
        $liste_ligne_be = LigneBonEntree::all();
        if (count($liste_ligne_be)>0) {
            for ($j=0; $j <count($liste_ligne_be) ; $j++) {
                $detail_prod = Produit::where('id',$liste_ligne_be[$j]->prod_id)->get();
                array_push($ligne_belist,
                    [
                        'id'=>$liste_ligne_be[$j]->id,
                        'IDBENTREE'=>$liste_ligne_be[$j]->bon_entree_id,
                        'code_prod' => $detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'code_a_barre'=>$detail_prod[0]->code_barre,
                        'qte_entree'=>$liste_ligne_be[$j]->qte_entree,
                        'pu_prod_entree'=>$liste_ligne_be[$j]->pu_prod_entree,
                        'total_ligne_entree'=>$liste_ligne_be[$j]->total_ligne_entree,

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $bon_entreelist,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'liste_ligne_be' => $ligne_belist,
            "message" => '',
        ]);

    }
    public function search_bon_entree(Request $request){
        $du_date = $request->du_date;
        $au_date = $request->au_date;


        $bon_entreelist =[];
        $liste = BonEntree::where('date_be','>=',$du_date)
            ->where('date_be','<=',$au_date)
            ->orderBy('date_be','desc')
            ->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($bon_entreelist,
                    [
                        'IDBENTREE'=>$liste[$j]->id,
                        'code_be'=>$liste[$j]->code_be,
                        'date_be'=>$liste[$j]->date_be,
                        'totah_ht_be'=>$liste[$j]->totah_ht_be,
                        'total_ttc_be'=>$liste[$j]->total_ttc_be,
                        'raison_soc_fourn'=>Fournisseur::where('id',$liste[$j]->fournisseur_id)->value('raison_soc_fourn'),
                        'contact_fourn'=>Fournisseur::where('id',$liste[$j]->fournisseur_id)->value('contact_fourn'),

                    ]);
            }
        }


        $ligne_belist =[];
        $liste_ligne_be = LigneBonEntree::all();
        if (count($liste_ligne_be)>0) {
            for ($j=0; $j <count($liste_ligne_be) ; $j++) {
                $detail_prod = Produit::where('id',$liste_ligne_be[$j]->prod_id)->get();
                array_push($ligne_belist,
                    [
                        'id'=>$liste_ligne_be[$j]->id,
                        'IDBENTREE'=>$liste_ligne_be[$j]->IDBENTREE,
                        'code_prod' => $detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'code_a_barre'=>$detail_prod[0]->code_barre,
                        'qte_entree'=>$liste_ligne_be[$j]->qte_entree,
                        'pu_prod_entree'=>$liste_ligne_be[$j]->pu_prod_entree,
                        'total_ligne_entree'=>$liste_ligne_be[$j]->total_ligne_entree,

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $bon_entreelist,
            'du_date' => $du_date,
            'au_date' => $au_date,
            'liste_ligne_be' => $ligne_belist,
            "message" => '',
        ]);

    }
    public function create_bon_entree(){
        $liste_fournisseur = Fournisseur::all();
        $liste_cat = CategProduit::all();
        $liste_produit = Produit::orderBy('id','desc')->get();
        return response()->json([
            "status" => true,
            'liste_fournisseur' => $liste_fournisseur,
            'liste_cat' => $liste_cat,
            'liste_produit' => $liste_produit,
            "message" => '',
        ]);
    }
    public function add_bon_entree(Request $request){
        $max_bon_en = BonEntree::max('id')+1;
        $currentYear = date('Y');
        $code = "BE-".$max_bon_en."-".$currentYear;

        BonEntree::create([
            'code_be'=>$code,
            'date_be'=>$request->date_be,
            // 'totah_ht_be'=>$request->pu_vente_prod,
            'total_ttc_be'=>$request->total_ttc_be,
            'fournisseur_id'=>$request->fournisseur_id,
        ]);

        $list_prod_selectionnes = explode("|", $request->list_prod_selectionnes);
        $list_qe_selectionnes = explode("|", $request->list_qe_selectionnes);
        $list_pu_selectionnes = explode("|", $request->list_pu_selectionnes);
        $list_total_selectionnes = explode("|", $request->list_total_selectionnes);
        if ($list_prod_selectionnes != null) {

            if (count($list_prod_selectionnes) > 1) {
                for ($i=0;$i<count($list_prod_selectionnes);$i++) {

                    if ($list_prod_selectionnes[$i] != "") {
                        LigneBonEntree::create([
                            'prod_id'=>$list_prod_selectionnes[$i],
                            'qte_entree'=>$list_qe_selectionnes[$i],
                            'pu_prod_entree'=>$list_pu_selectionnes[$i],
                            'total_ligne_entree'=>$list_total_selectionnes[$i],
                            'bon_entree_id'=>BonEntree::max('id'),
                        ]);
                        $verif_stock = StockProduit::where('prod_id',$list_prod_selectionnes[$i])->get();
                        if(count($verif_stock)>0){
                            $nv_qte = intval($verif_stock[0]->qte_stk) + intval($list_qe_selectionnes[$i]);
                            StockProduit::where('prod_id',$list_prod_selectionnes[$i])
                                ->update([
                                    'qte_stk'=>  $nv_qte."",
                                ]);
                        }
                        else{
                            StockProduit::create([
                                'prod_id'=>$list_prod_selectionnes[$i],
                                'qte_stk'=>$list_qe_selectionnes[$i],
                            ]);
                        }
                    }
                }
            }
        }


        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_bon_entree($id){
        $detail = BonEntree::where('id',$id)->get();
        $liste_fournisseur = Fournisseur::all();
        $raison_soc_fourn = Fournisseur::where('id',$detail[0]->fournisseur_id)->value('raison_soc_fourn');
        $liste_cat = CategProduit::all();

        $ligne_belist =[];
        $liste = LigneBonEntree::where('bon_entree_id',$detail[0]->id)->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                $detail_prod =Produit::where('id',$liste[$j]->prod_id)->get();
                array_push($ligne_belist,
                    [
                        'IDPROD'=>$liste[$j]->prod_id,
                        'code_prod' => $detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'qte_entree'=>$liste[$j]->qte_entree,
                        'pu_prod_entree'=>$liste[$j]->pu_prod_entree,
                        'total_ligne_entree'=>$liste[$j]->total_ligne_entree,

                    ]);
            }
        }
        $liste_produit = Produit::orderBy('id','desc')->get();
        return response()->json([
            "status" => true,
            'detail' => $detail,
            'liste_fournisseur' => $liste_fournisseur,
            'raison_soc_fourn' => $raison_soc_fourn,
            'liste_cat' => $liste_cat,
            'liste' => $ligne_belist,
            'liste_produit' => $liste_produit,
            "message" => '',
        ]);

    }
    public function update_bon_entree(Request $request,$id){
        BonEntree::where('id',$id)
            ->update([
                'date_be'=>$request->date_be,
                // 'totah_ht_be'=>$request->pu_vente_prod,
                'total_ttc_be'=>$request->total_ttc_be,
                'fournisseur_id'=>$request->IDFOURNISSEUR,
            ]);

        $list_prod_selectionnes = explode("|", $request->list_prod_selectionnes);
        $list_qe_selectionnes = explode("|", $request->list_qe_selectionnes);
        $list_pu_selectionnes = explode("|", $request->list_pu_selectionnes);
        $list_total_selectionnes = explode("|", $request->list_total_selectionnes);
        if ($list_prod_selectionnes != null) {

            if (count($list_prod_selectionnes) > 1) {
                LigneBonEntree::where('bon_entree_id',$id)->delete();
                for ($i=0;$i<count($list_prod_selectionnes);$i++) {

                    if ($list_prod_selectionnes[$i] != "") {

                        LigneBonEntree::create([
                            'prod_id'=>$list_prod_selectionnes[$i],
                            'qte_entree'=>$list_qe_selectionnes[$i],
                            'pu_prod_entree'=>$list_pu_selectionnes[$i],
                            'total_ligne_entree'=>$list_total_selectionnes[$i],
                            'bon_entree_id'=>$id,
                        ]);

                        $verif_stock =StockProduit::where('prod_id',$list_prod_selectionnes[$i])->get();
                        if(count($verif_stock)>0){
                            $nv_qte = intval($verif_stock[0]->qte_stk) + intval($list_qe_selectionnes[$i]);
                            StockProduit::where('prod_id',$list_prod_selectionnes[$i])
                                ->update([
                                    'qte_stk'=>  $nv_qte."",
                                ]);
                        }
                        else{
                            StockProduit::create([
                                'prod_id'=>$list_prod_selectionnes[$i],
                                'qte_stk'=>$list_qe_selectionnes[$i],
                            ]);
                        }
                    }
                }
            }
        }


        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_bon_entree(Request $request){
        $id_bon_en = $request->champ_id;
        LigneBonEntree::where('bon_entree_id',$id_bon_en)->delete();
        BonEntree::where('id',$id_bon_en)->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function index_bon_sortie(){
        $currentDate = Carbon::now();

        $bon_sortielist =[];
        $liste = BonSortie::where('date_bs','>=',date('Y-m-01'))
            ->where('date_bs','<=',$currentDate->endOfMonth()->format('Y-m-d'))
            ->orderBy('date_bs','desc')
            ->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($bon_sortielist,
                    [
                        'IDBSORTIE'=>$liste[$j]->id,
                        'code_bs'=>$liste[$j]->code_bs,
                        'date_bs'=>$liste[$j]->date_bs,
                        'total_ht_bs'=>$liste[$j]->total_ht_bs,
                        'total_ttc_bs'=>$liste[$j]->total_ttc_bs,

                    ]);
            }
        }


        $ligne_bslist =[];
        $liste_ligne_be = LigneBonSortie::all();
        if (count($liste_ligne_be)>0) {
            for ($j=0; $j <count($liste_ligne_be) ; $j++) {
                $detail_prod = Produit::where('id',$liste_ligne_be[$j]->prod_id)->get();
                array_push($ligne_bslist,
                    [
                        'IDBSORTIE'=>$liste_ligne_be[$j]->IDBSORTIE,
                        'code_prod' => $detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'code_a_barre'=>$detail_prod[0]->code_barre,
                        'qte_sortie'=>$liste_ligne_be[$j]->qte_sortie,
                        'pu_sortie'=>$liste_ligne_be[$j]->pu_sortie,
                        'total_ligne_st'=>$liste_ligne_be[$j]->total_ligne_st,

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $bon_sortielist,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'liste_ligne_be' => $ligne_bslist,
            "message" => '',
        ]);

    }
    public function search_bon_sortie(Request $request){
        $du_date = $request->du_date;
        $au_date = $request->au_date;


        $bon_sortielist =[];
        $liste = BonSortie::where('date_bs','>=',$du_date)
            ->where('date_bs','<=',$au_date)
            ->orderBy('date_bs','desc')
            ->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($bon_sortielist,
                    [
                        'IDBSORTIE'=>$liste[$j]->id,
                        'code_bs'=>$liste[$j]->code_bs,
                        'date_bs'=>$liste[$j]->date_bs,
                        'total_ht_bs'=>$liste[$j]->total_ht_bs,
                        'total_ttc_bs'=>$liste[$j]->total_ttc_bs,

                    ]);
            }
        }


        $ligne_bslist =[];
        $liste_ligne_be = LigneBonSortie::all();
        if (count($liste_ligne_be)>0) {
            for ($j=0; $j <count($liste_ligne_be) ; $j++) {
                $detail_prod = Produit::where('id',$liste_ligne_be[$j]->prod_id)->get();
                array_push($ligne_bslist,
                    [
                        'IDBSORTIE'=>$liste_ligne_be[$j]->IDBSORTIE,
                        'code_prod' => $detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'code_a_barre'=>$detail_prod[0]->code_barre,
                        'qte_sortie'=>$liste_ligne_be[$j]->qte_sortie,
                        'pu_sortie'=>$liste_ligne_be[$j]->pu_sortie,
                        'total_ligne_st'=>$liste_ligne_be[$j]->total_ligne_st,

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $bon_sortielist,
            'du_date' => $du_date,
            'au_date' => $au_date,
            'liste_ligne_be' => $ligne_bslist,
            "message" => '',
        ]);

    }
    public function create_bon_sortie(){
        $liste_cat = CategProduit::all();
        $liste_produit = Produit::orderBy('id','desc')->get();
        return response()->json([
            "status" => true,
            'liste_cat' => $liste_cat,
            'liste_produit' => $liste_produit,
            "message" => '',
        ]);
    }
    public function add_bon_sortie(Request $request){
        $status=false;
        $message="";
        $max_bon_en = BonSortie::max('id')+1;
        $currentYear = date('Y');
        $code = "BS-".$max_bon_en."-".$currentYear;

        BonSortie::create([
            'code_bs'=>$code,
            'date_bs'=>$request->date_bs,
            // 'total_ht_bs'=>$request->pu_vente_prod,
            'total_ttc_bs'=>$request->total_ttc_bs,
        ]);

        $list_prod_selectionnes = explode("|", $request->list_prod_selectionnes);
        $list_qe_selectionnes = explode("|", $request->list_qe_selectionnes);
        $list_pu_selectionnes = explode("|", $request->list_pu_selectionnes);
        $list_total_selectionnes = explode("|", $request->list_total_selectionnes);
        if ($list_prod_selectionnes != null) {

            if (count($list_prod_selectionnes) > 1) {
                for ($i=0;$i<count($list_prod_selectionnes);$i++) {

                    if ($list_prod_selectionnes[$i] != "") {
                        LigneBonSortie::create([
                            'prod_id'=>$list_prod_selectionnes[$i],
                            'qte_sortie'=>$list_qe_selectionnes[$i],
                            'pu_sortie'=>$list_pu_selectionnes[$i],
                            'total_ligne_st'=>$list_total_selectionnes[$i],
                            'IDBSORTIE'=>BonSortie::max('id'),
                        ]);

                        $verif_stock = StockProduit::where('id',$list_prod_selectionnes[$i])->get();
                        if(count($verif_stock)>0){
                            $nv_qte = intval($verif_stock[0]->qte_stk) + intval($list_qe_selectionnes[$i]);
                            StockProduit::where('id',$list_prod_selectionnes[$i])
                                ->update([
                                    'qte_stk'=>  $nv_qte."",
                                ]);
                        }
                        else{
                            $status=false;
                            $message="le produit ".Produit::where('id',$list_prod_selectionnes[$i])->value('desc')." est indisponible!";
                        }

                    }
                }
            }
        }

        $status=true;
        $message="content.Ajout_terminée";
        return response()->json([
            "status" => $status,
            'message' => $message,
        ]);
    }
    public function edit_bon_sortie($id){
        $detail = BonSortie::where('id',$id)->get();
        $liste_cat = CategProduit::all();

        $ligne_bslist =[];
        $liste = LigneBonSortie::where('IDBSORTIE',$detail[0]->id)->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                $detail_prod = produit::where('id',$liste[$j]->prod_id)->get();
                array_push($ligne_bslist,
                    [
                        'IDPROD'=>$liste[$j]->prod_id,
                        'code_prod'=>$detail_prod[0]->code,
                        'des_prod'=>$detail_prod[0]->desc,
                        'qte_sortie'=>$liste[$j]->qte_sortie,
                        'pu_sortie'=>$liste[$j]->pu_sortie,
                        'total_ligne_st'=>$liste[$j]->total_ligne_st,

                    ]);
            }
        }
        $liste_produit = Produit::orderBy('id','desc')->get();
        return response()->json([
            "status" => true,
            'detail' => $detail,
            'liste_cat' => $liste_cat,
            'liste' => $ligne_bslist,
            'liste_produit' => $liste_produit,
            "message" => '',
        ]);
    }
    public function update_bon_sortie(Request $request,$id){
        BonSortie::where('id',$id)
            ->update([
                'date_bs'=>$request->date_bs,
                // 'total_ht_bs'=>$request->pu_vente_prod,
                'total_ttc_bs'=>$request->total_ttc_bs,
            ]);

        $list_prod_selectionnes = explode("|", $request->list_prod_selectionnes);
        $list_qe_selectionnes = explode("|", $request->list_qe_selectionnes);
        $list_pu_selectionnes = explode("|", $request->list_pu_selectionnes);
        $list_total_selectionnes = explode("|", $request->list_total_selectionnes);
        if ($list_prod_selectionnes != null) {

            if (count($list_prod_selectionnes) > 1) {
                LigneBonSortie::where('IDBSORTIE',$id)->delete();
                for ($i=0;$i<count($list_prod_selectionnes);$i++) {

                    if ($list_prod_selectionnes[$i] != "") {

                        LigneBonSortie::create([
                            'prod_id'=>$list_prod_selectionnes[$i],
                            'qte_sortie'=>$list_qe_selectionnes[$i],
                            'pu_sortie'=>$list_pu_selectionnes[$i],
                            'total_ligne_st'=>$list_total_selectionnes[$i],
                            'IDBSORTIE'=>$id,

                        ]);

                        $verif_stock = StockProduit::where('id',$list_prod_selectionnes[$i])->get();
                        if(count($verif_stock)>0){
                            $nv_qte = intval($verif_stock[0]->qte_stk) + intval($list_qe_selectionnes[$i]);
                            StockProduit::where('id',$list_prod_selectionnes[$i])
                                ->update([
                                    'qte_stk'=>  $nv_qte."",
                                ]);
                        }
                    }
                }
            }
        }


        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_bon_sortie(Request $request){
        $id_bon_en = $request->champ_id;
        LigneBonSortie::where('IDBSORTIE',$id_bon_en)->delete();
        BonSortie::where('id',$id_bon_en)->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
