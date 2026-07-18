<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Instructeur;
use App\Models\Pays;
use Illuminate\Http\Request;

class PaysController extends Controller
{
    public function index(){
        $liste = Pays::all();
        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add(Request $request){
        $max_id = Pays::max('id');
        $code = 'P_00'.$max_id;
        Pays::create([
            'code'=>$code,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit($id,Request $request){
        $detail = Pays::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update($id,Request $request){
        Pays::where('id',$id)
            ->update([
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete(Request $request){
        $id = $request->champ_id;
        Instructeur::where('pays_id',$id)
            ->delete();
        Pays::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
