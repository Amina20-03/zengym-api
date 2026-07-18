<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Operation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function index_compte(){
        $comptelist =[];
        $liste = Compte::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($comptelist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'date'=>$liste[$j]->date,
                        'datedernmodif'=>$liste[$j]->datedernmodif,
                        'soldecpte'=>$liste[$j]->soldecpte,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                    ]);
            }
        }
        $list_instructeurs = User::where('instructeur_id','!=',null)->get();

        return response()->json([
            "status" => true,
            'liste' => $comptelist,
            'list_instructeurs' => $list_instructeurs,
            "message" => '',
        ]);
    }
    public function index_operation($id_compte){
        $currentDate = Carbon::now();
        $detail_compte = Compte::where('id',$id_compte)->get();
        $instructeur = User::where('instructeur_id',$detail_compte[0]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$detail_compte[0]->instructeur_id)->value('prenom');
        $insctructeur_id = $detail_compte[0]->instructeur_id;
        $list_operations = Operation::where('compte_id',$id_compte)
            ->where('date','>=',date('Y-m-01'))
            ->where('date','<=',$currentDate->endOfMonth()->format('Y-m-d'))
            ->orderBy('date','desc')
            ->get();
        return response()->json([
            "status" => true,
            'liste' => $list_operations,
            'detail_compte' => $detail_compte,
            'instructeur' => $instructeur,
            'insctructeur_id' => $insctructeur_id,
            "message" => '',
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
        ]);
    }
    public function search_operation(Request $request){
        $detail_compte = Compte::where('id',$request->id_compte)->get();
        $du_date = $request->du_date;
        $au_date = $request->au_date;
        $instructeur = User::where('instructeur_id',$detail_compte[0]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$detail_compte[0]->instructeur_id)->value('prenom');
        $insctructeur_id = $detail_compte[0]->instructeur_id;
        $list_operations = Operation::where('compte_id',$request->id_compte)
            ->where('date','>=',$du_date)
            ->where('date','<=',$au_date)
            ->orderBy('date','desc')
            ->get();
        return response()->json([
            "status" => true,
            'liste' => $list_operations,
            'detail_compte' => $detail_compte,
            'instructeur' => $instructeur,
            'insctructeur_id' => $insctructeur_id,
            'du_date' => $du_date,
            'au_date' => $au_date,
            "message" => '',
        ]);
    }
    public function add_operation(Request $request){
        $max_id = Operation::max('id');
        $code = 'OP_00'.$max_id;
        Operation::create([
                'code'=>$code,
                'date'=>now(),
                'montant'=>$request->montant,
                'compte_id'=>$request->compte_id,
                'type'=>'Débit',
            ]);
        Compte::where('id',$request->compte_id)
            ->update([
                'soldecpte'=>floatval($request->soldecpte)-floatval($request->montant),
                'datedernmodif'=>now(),
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
}
