<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CategInstructeur;
use App\Models\Pourcentage;
use Illuminate\Http\Request;

class PourcentageController extends Controller
{
    public function index(){
        $pourcentagelist =[];
        $liste = Pourcentage::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($pourcentagelist,
                    [
                        'id'=>$liste[$j]->id,
                        'pr_client'=>$liste[$j]->pr_client,
                        'pr_prod'=>$liste[$j]->pr_prod,
                        'pr_formation'=>$liste[$j]->pr_formation,
                        'categ_instructeur_desc'=>CategInstructeur::where('id',$liste[$j]->cat_inst_id)->value('desc'),
                    ]);
            }
        }
        $list_cat = CategInstructeur::all();
        return response()->json([
            "status" => true,
            'liste' => $pourcentagelist,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add(Request $request){

        Pourcentage::create([
            'pr_client'=>$request->pr_client,
            'pr_prod'=>$request->pr_prod,
            'pr_formation'=>$request->pr_formation,
            'cat_inst_id'=>$request->cat_inst_id,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit($id,Request $request){
        $detail = Pourcentage::where('id',$id)->get();
        $list_cat = CategInstructeur::all();
        $desc_cat = CategInstructeur::where('id',$detail[0]->cat_inst_id)->value('desc');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
            "message" => '',
        ]);
    }
    public function update($id,Request $request){
        Pourcentage::where('id',$id)
            ->update([
                'pr_client'=>$request->pr_client,
                'pr_prod'=>$request->pr_prod,
                'pr_formation'=>$request->pr_formation,
                'cat_inst_id'=>$request->cat_inst_id,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete(Request $request){
        $id = $request->champ_id;
        Pourcentage::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
