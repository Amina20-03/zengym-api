<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CategInstructeur;
use App\Models\CategRep;
use App\Models\Pays;
use App\Models\Representant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RepresentantController extends Controller
{
    public function index_cat_rep(){
        $liste = CategRep::all();
        return response()->json([
            "status" => true,
            "message" => '',
            'liste' => $liste,
        ]);
    }
    public function add_cat_rep(Request $request){
        $max_id = CategRep::max('id');
        $code = 'CR_00'.$max_id;
        CategRep::create([
            'code'=>$code,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_cat_rep($id,Request $request){
        $detail = CategRep::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "message" => '',
            "detail" => $detail,
        ]);
    }
    public function update_cat_rep($id,Request $request){
        CategRep::where('id',$id)
            ->update([
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_cat_rep(Request $request){
        $id = $request->champ_id;
        Representant::where('categ_rep_id',$id)
            ->delete();
        CategRep::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_representant(){
        $representantlist =[];
        $liste = Representant::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($representantlist,
                    [
                        'id'=>$liste[$j]->id,
                        'raison_social'=>$liste[$j]->raison_social,
                        'contact'=>$liste[$j]->contact,
                        'mf'=>$liste[$j]->mf,
                        'rc'=>$liste[$j]->rc,
                        'localisation'=>$liste[$j]->localisation,
                        'categ_rep_desc'=>CategRep::where('id',$liste[$j]->categ_rep_id)->value('desc'),
                        'nom'=>User::where('representant_id',$liste[$j]->id)->value('nom'),
                        'prenom'=>User::where('representant_id',$liste[$j]->id)->value('prenom'),
                        'mail'=>User::where('representant_id',$liste[$j]->id)->value('mail'),
                        'adresse'=>User::where('representant_id',$liste[$j]->id)->value('adresse'),
                        'tel'=>User::where('representant_id',$liste[$j]->id)->value('tel'),
                        'email'=>User::where('representant_id',$liste[$j]->id)->value('email'),
                    ]);
            }
        }
        $list_cat = CategRep::all();
        return response()->json([
            "status" => true,
            'liste' => $representantlist,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add_representant(Request $request){
        $status=false;
        $message='';
        if($request->mail != null){
            $verif = User::where('mail', $request->mail)->where('representant_id','!=',null)
                ->count();
            if($verif>0){
                $status=false;
                $message='content.Email_existe_déjà';

            }
            else{
                Representant::create([
                    'raison_social'=>$request->raison_social,
                    'contact'=>$request->contact,
                    'mf'=>$request->mf,
                    'rc'=>$request->rc,
                    'localisation'=>$request->localisation,
                    'categ_rep_id'=>$request->categ_rep_id,

                ]);
                User::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'mail'=>$request->mail,
                    'adresse'=>$request->adresse,
                    'tel'=>$request->tel,
                    'email'=>$request->email,
                    'role'=>'REPRESANTANT',
                    'password'=>Hash::make($request->password),
                    'representant_id'=>Representant::max('id'),
                ]);
                $status=true;
                $message='content.Ajout_terminée';
            }
        }

        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }
    public function edit_representant($id,Request $request){
        $detail = Representant::where('id',$id)->get();
        $detail_user = User::where('representant_id',$id)->get();
        $list_cat = CategRep::all();
        $desc_cat = CategRep::where('id',$detail[0]->categ_rep_id)->value('desc');

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "detail_user" => $detail_user,
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
            "message" => '',
        ]);
    }
    public function update_representant($id,Request $request){
        $status=false;
        $message='';
        if($request->mail_old != $request->mail){
            $verif = User::where('mail', $request->mail)->where('representant_id','!=',null)
                ->count();
            if($verif>0){
                $status = false;
                $message = 'content.Email_existe_déjà';

            }
            else{
                Representant::where('id',$id)
                    ->update([
                        'raison_social'=>$request->raison_social,
                        'contact'=>$request->contact,
                        'mf'=>$request->mf,
                        'rc'=>$request->rc,
                        'localisation'=>$request->localisation,
                        'categ_rep_id'=>$request->categ_rep_id,
                    ]);
                User::where('representant_id',$id)
                    ->update([
                        'nom'=>$request->nom,
                        'prenom'=>$request->prenom,
                        'mail'=>$request->mail,
                        'adresse'=>$request->adresse,
                        'tel'=>$request->tel,
                        'email'=>$request->email,
                    ]);
                $status = true;
                $message = 'content.Modification_terminée';
            }
        }
        else{
            Representant::where('id',$id)
                ->update([
                    'raison_social'=>$request->raison_social,
                    'contact'=>$request->contact,
                    'mf'=>$request->mf,
                    'rc'=>$request->rc,
                    'localisation'=>$request->localisation,
                    'categ_rep_id'=>$request->categ_rep_id,
                ]);
            User::where('representant_id',$id)
                ->update([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'adresse'=>$request->adresse,
                    'tel'=>$request->tel,
                    'email'=>$request->email,
                ]);
            $status = true;
            $message = 'content.Modification_terminée';
        }

        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);

    }
    public function update_representant_password($id,Request $request){
        $status = false;
        $message = "";
        $ancienpassword = $request->ancienpassword;
        $password = $request->password;
        $conf_password = $request->conf_password;
        $user_password = User::where('representant_id',$id)
            ->value('password');

        if (Hash::check($ancienpassword, $user_password)) {
            if ($password == $ancienpassword) {
                $status = false;
                $message = 'content.Vous_avez_indiquer_un_ancien_mot_de_passe';
            }
            else{
                if ($password != $conf_password) {
                    $status = false;
                    $message = 'content.La_confirmation_du_mot_de_passe_ne_correspond_pas';
                }
                else{
                    User::where('representant_id',$id)
                        ->update([
                            'password'=>Hash::make($request->password),
                        ]);
                    $status = true;
                    $message = 'content.Modification_terminée';
                }
            }
        }
        else{
            $status = false;
            $message = 'content.Ancien_Mot_De_Passe_Incorrect';
        }
        return response()->json([
            "status" => $status,
            'message' => $message,
        ]);
    }
    public function delete_representant(Request $request){
        $id = $request->champ_id;
        User::where('representant_id',$id)
            ->delete();
        Representant::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
