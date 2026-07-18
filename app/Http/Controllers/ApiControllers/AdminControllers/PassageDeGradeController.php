<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\CategCandidat;
use App\Models\CategInstructeur;
use App\Models\CategRep;
use App\Models\Compte;
use App\Models\Cours;
use App\Models\Evenement;
use App\Models\Instructeur;
use App\Models\PassageDeGrade;
use App\Models\Representant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\In;

class PassageDeGradeController extends Controller
{
    public function index_passage_grade(){
        $PGlist =[];
        $liste = PassageDeGrade::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($PGlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'des'=>$liste[$j]->des,
                        'nbr_event'=>$liste[$j]->nbr_event,
                        'nbr_masterclass'=>$liste[$j]->nbr_masterclass,

                        'categ_instructeur_desc_1'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id_1)->value('desc'),
                        'categ_instructeur_desc_2'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id_2)->value('desc'),

                    ]);
            }
        }
        $list_cat = CategInstructeur::all();
        return response()->json([
            "status" => true,
            'liste' => $PGlist,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add_passage_grade(Request $request){
        $max_id = PassageDeGrade::max('id')+1;
        $code = 'PG_00'.$max_id;
        PassageDeGrade::create([
            'code'=>$code,
            'des'=>$request->des,
            'nbr_event'=>$request->nbr_event,
            'nbr_masterclass'=>$request->nbr_masterclass,
            'categ_instructeur_id_1'=>$request->categ_instructeur_id_1,
            'categ_instructeur_id_2'=>$request->categ_instructeur_id_2,

            'created_at' => now(),
        ]);

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_passage_grade($id,Request $request){
        $detail = PassageDeGrade::where('id',$id)->get();
        $detail_categ_instructeur_id_1 = CategInstructeur::where('id',$detail[0]->categ_instructeur_id_1)->get();
        $detail_categ_instructeur_id_2 = CategInstructeur::where('id',$detail[0]->categ_instructeur_id_2)->get();
        $list_cat = CategInstructeur::all();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "detail_categ_instructeur_id_1" => $detail_categ_instructeur_id_1,
            "detail_categ_instructeur_id_2" => $detail_categ_instructeur_id_2,
            "list_cat" => $list_cat,
            "message" => '',
        ]);
    }
    public function update_passage_grade($id,Request $request){
        PassageDeGrade::where('id',$id)
            ->update([
                'des'=>$request->des,
                'nbr_event'=>$request->nbr_event,
                'nbr_masterclass'=>$request->nbr_masterclass,
                'categ_instructeur_id_1'=>$request->categ_instructeur_id_1,
                'categ_instructeur_id_2'=>$request->categ_instructeur_id_2,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_passage_grade(Request $request){
        $id = $request->champ_id;

        PassageDeGrade::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function get_users(){
        $userslist =[];
        $liste1 = User::where('admin_id',null)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($userslist,
                    [
                        'id'=>$liste1[$j]->id,
                        'nom'=>$liste1[$j]->nom,
                        'prenom'=>$liste1[$j]->prenom,
                        'mail'=>$liste1[$j]->mail,
                        'adresse'=>$liste1[$j]->adresse,
                        'tel'=>$liste1[$j]->tel,
                        'admin_id'=>$liste1[$j]->admin_id,
                        'instructeur_id'=>$liste1[$j]->instructeur_id,
                        'representant_id'=>$liste1[$j]->representant_id,
                        'candidat_id'=>$liste1[$j]->candidat_id,

                        'categ_candidat_desc'=>CategCandidat::where('id',Candidat::where('id',$liste1[$j]->candidat_id)->value('categ_candidat_id'))->value('desc'),
                        'categ_instructeur_desc'=>CategInstructeur::where('id',Instructeur::where('id',$liste1[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),
                        'categ_rep_desc'=>CategRep::where('id',Representant::where('id',$liste1[$j]->representant_id)->value('categ_rep_id'))->value('desc'),

                        'nbr_events'=>Evenement::where('instructeur_id',$liste1[$j]->instructeur_id)->count(),
                        'nbr_masterclass'=>Cours::where('instructeur_id',$liste1[$j]->instructeur_id)->count(),

                    ]);
            }
        }


        $PGlist =[];
        $liste = PassageDeGrade::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($PGlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'des'=>$liste[$j]->des,
                        'nbr_event'=>$liste[$j]->nbr_event,
                        'nbr_masterclass'=>$liste[$j]->nbr_masterclass,

                        'categ_instructeur_desc_1'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id_1)->value('desc'),
                        'categ_instructeur_desc_2'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id_2)->value('desc'),

                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'PGlist' => $PGlist,
            'liste' => $userslist,
            "message" => '',
        ]);
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function devenir_instructeur($id_user){
        $detail_cand = User::where('id',$id_user)->get();
        $categ_inst = CategInstructeur::where('ordre','0')->get();
        Instructeur::create([

            'categ_instructeur_id'=>$categ_inst[0]->id,
        ]);
        User::where('candidat_id',$detail_cand[0]->candidat_id)->update([

            'role'=>'CANDIDAT',
            'instructeur_id'=>Instructeur::max('id'),
        ]);
        $code_compte = 'CI_'.Instructeur::max('id');
        Compte::create([
            'code'=>$code_compte,
            'date'=>now(),
            'datedernmodif'=>now(),
            'soldecpte'=>'0',
            'instructeur_id'=>Instructeur::max('id'),
        ]);


        $status=true;
        $message = 'content.Modification_terminée';
        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }
    public function passage_grade_instructeur($id_user){
        $detail_user= User::where('id',$id_user)->get();
        $detail_instr = Instructeur::where('id',$detail_user[0]->instructeur_id)->get();
        $nbr_events = Evenement::where('instructeur_id',$detail_user[0]->instructeur_id)->count();
        $nbr_masterclass = Cours::where('instructeur_id',$detail_user[0]->instructeur_id)->count();
        $verif_passage = PassageDeGrade::where('nbr_event','<=',$nbr_events)->where('nbr_masterclass','<=',$nbr_masterclass)->get();
        if(count($verif_passage)>0){
            if($verif_passage[0]->categ_instructeur_id_1 == $detail_instr[0]->categ_instructeur_id){
                $detail_categ_instr = CategInstructeur::where('id',$detail_instr[0]->categ_instructeur_id)->get();
                if($detail_categ_instr[0]->ordre == '2'){
                    Representant::create([

                        'categ_rep_id'=>'AMBASSADEUR',
                    ]);
                    User::where('instructeur_id',$detail_instr[0]->id)->update([

                        'role'=>'REPRESANTANT',
                        'representant_id'=>Representant::max('id'),
                    ]);

                }
                else{
                    Instructeur::where('id',$detail_instr[0]->id)->update([
                        'categ_instructeur_id' => $verif_passage[0]->categ_instructeur_id_2
                    ]);
                }

                $status=true;
                $message = 'content.Modification_terminée';
            }
        }
        else{
            $status=false;
            $message = 'error';
        }


        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }
}
