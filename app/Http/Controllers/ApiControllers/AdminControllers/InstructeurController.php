<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\CategInstructeur;
use App\Models\Compte;
use App\Models\CoursUser;
use App\Models\Instructeur;
use App\Models\Pays;
use App\Models\User;
use App\Models\VenteAbo;
use App\Models\VenteProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructeurController extends Controller
{
    public function index_cat_instructeur(){
        $liste = CategInstructeur::all();
        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_cat_instructeur(Request $request){
        $max_id = CategInstructeur::max('id');
        $code = 'CI_00'.$max_id;
        CategInstructeur::create([
            'code'=>$code,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_cat_instructeur($id,Request $request){
        $detail = CategInstructeur::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_cat_instructeur($id,Request $request){
        CategInstructeur::where('id',$id)
            ->update([
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_cat_instructeur(Request $request){
        $id = $request->champ_id;
        Instructeur::where('categ_instructeur_id',$id)
            ->delete();
        CategInstructeur::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_instructeur(){
        $instructeurlist =[];
        $liste = Instructeur::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($instructeurlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code_instr'=>$liste[$j]->code_instr,
                        'profession'=>$liste[$j]->profession,
                        'diplome'=>$liste[$j]->diplome,
                        'commentaire'=>$liste[$j]->commentaire,
                        'sexe'=>$liste[$j]->sexe,
                        'date_naiss'=>$liste[$j]->date_naiss,
                        'photo'=>$liste[$j]->photo,
                        'cin'=>$liste[$j]->cin,
                        'pays_desc'=>Pays::where('id',$liste[$j]->pays_id)->value('desc'),
                        'categ_instructeur_desc'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id)->value('desc'),
                        'nom'=>User::where('instructeur_id',$liste[$j]->id)->value('nom'),
                        'prenom'=>User::where('instructeur_id',$liste[$j]->id)->value('prenom'),
                        'mail'=>User::where('instructeur_id',$liste[$j]->id)->value('mail'),
                        'adresse'=>User::where('instructeur_id',$liste[$j]->id)->value('adresse'),
                        'tel'=>User::where('instructeur_id',$liste[$j]->id)->value('tel'),
                        'email'=>User::where('instructeur_id',$liste[$j]->id)->value('email'),
                        'role'=>User::where('instructeur_id',$liste[$j]->id)->value('role'),

                    ]);
            }
        }
        $list_pays = Pays::all();
        $list_cat = CategInstructeur::all();
        return response()->json([
            "status" => true,
            'liste' => $instructeurlist,
            'list_pays' => $list_pays,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add_instructeur(Request $request){
        $status=false;
        $message='';

            $verif = User::where('mail', $request->mail)->where('instructeur_id','!=',null)
                ->count();
            $verif_login = User::where('email', $request->email)
            ->count();
            if($verif>0){
                $status=false;
                $message='content.Email_existe_déjà';

            }
            elseif($verif_login>0){
                $status=false;
                $message='content.login_existe_déjà';

            }
            else{
                Instructeur::create([
                    'code_instr'=>$request->code_instr,
                    'profession'=>$request->profession,
                    'commentaire'=>$request->commentaire,
                    'sexe'=>$request->sexe,
                    'date_naiss'=>$request->date_naiss,
                    'photo'=>$request->photo,
                    'cin'=>$request->cin,
                    'pays_id'=>$request->pays_id,
                    'categ_instructeur_id'=>$request->categ_instructeur_id,
                ]);
                User::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'mail'=>$request->mail,
                    'adresse'=>$request->adresse,
                    'tel'=>$request->tel,
                    'email'=>$request->email,
                    'role'=>'INSTRUCTEUR',
                    'password'=>Hash::make($request->password),
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
                $message='content.Ajout_terminée';
            }


        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }
    public function edit_instructeur($id,Request $request){
        $detail = Instructeur::where('id',$id)->get();
        $detail_user = User::where('instructeur_id',$id)->get();
        $list_pays = Pays::all();
        $desc_pays = Pays::where('id',$detail[0]->pays_id)->value('desc');
        $list_cat = CategInstructeur::all();
        $desc_cat = CategInstructeur::where('id',$detail[0]->categ_instructeur_id)->value('desc');

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "detail_user" => $detail_user,
            'list_pays' => $list_pays,
            'desc_pays' => $desc_pays,
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
            "message" => '',
        ]);
    }
    public function update_instructeur($id,Request $request){
        $status=false;
        $message='';
        if($request->mail_old != $request->mail){
            $verif = User::where('mail', $request->mail)->where('instructeur_id','!=',null)
                ->count();
            if($verif>0){
                $status = false;
                $message = 'content.Email_existe_déjà';

            }
            else{
                Instructeur::where('id',$id)
                    ->update([
                        'code_instr'=>$request->code_instr,
                        'profession'=>$request->profession,
                        'commentaire'=>$request->commentaire,
                        'sexe'=>$request->sexe,
                        'date_naiss'=>$request->date_naiss,
                        'photo'=>$request->photo,
                        'cin'=>$request->cin,
                        'pays_id'=>$request->pays_id,
                        'categ_instructeur_id'=>$request->categ_instructeur_id,
                    ]);
                User::where('instructeur_id',$id)
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
            Instructeur::where('id',$id)
                ->update([
                    'code_instr'=>$request->code_instr,
                    'profession'=>$request->profession,
                    'commentaire'=>$request->commentaire,
                    'sexe'=>$request->sexe,
                    'date_naiss'=>$request->date_naiss,
                    'photo'=>$request->photo,
                    'cin'=>$request->cin,
                    'pays_id'=>$request->pays_id,
                    'categ_instructeur_id'=>$request->categ_instructeur_id,
                ]);
            User::where('instructeur_id',$id)
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
    public function update_instructeur_password($id,Request $request){
        $status = false;
        $message = "";
      //  $ancienpassword = $request->ancienpassword;
        $password = $request->password;
        $conf_password = $request->conf_password;
        $user_password = User::where('instructeur_id',$id)
            ->value('password');

//        if (Hash::check($ancienpassword, $user_password)) {
//            if ($password == $ancienpassword) {
//                $status = false;
//                $message = 'content.Vous_avez_indiquer_un_ancien_mot_de_passe';
//            }
//            else{
//
//            }
//        }
        if ($password != $conf_password) {
            $status = false;
            $message = 'content.La_confirmation_du_mot_de_passe_ne_correspond_pas';
        }
        else{
            User::where('instructeur_id',$id)
                ->update([
                    'password'=>Hash::make($request->password),
                ]);
            $status = true;
            $message = 'content.Modification_terminée';
        }
//        else{
//            $status = false;
//            $message = 'content.Ancien_Mot_De_Passe_Incorrect';
//        }
        return response()->json([
            "status" => $status,
            'message' => $message,
        ]);
    }
    public function update_instructeur_diplome_status($id)
    {
        $user = Instructeur::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->diplome = !$user->diplome;
        $user->save();

        return response()->json([
            "status" => true,
            'message' => 'Diplome status updated successfully',
            'diplome' => $user->diplome
        ]);
    }

    public function delete_instructeur(Request $request){
        $id = $request->champ_id;
        VenteAbo::where('instructeur_id',$id)
            ->delete();
        $detail_user = User::where('instructeur_id',$id)->get();
        if(count($detail_user)>0)
            CoursUser::where('user_id',$detail_user[0]->id)->delete();
        User::where('instructeur_id',$id)
            ->delete();
        Candidat::where('instructeur_id',$id)
            ->delete();
        Compte::where('instructeur_id',$id)
            ->delete();
        VenteProd::where('instructeur_id',$id)
            ->delete();
        Instructeur::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_instructeur_by_categ($id_categ){


        $instructeurlist =[];
        $list_pays = Pays::all();
        $list_cat = CategInstructeur::orderBy('id','desc')->get();
        if ($id_categ == 'all'){
            $id_categ = $list_cat[0]->id;
        }

        $liste = Instructeur::where('categ_instructeur_id',$id_categ)->orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($instructeurlist,
                    [
                        'id'=>$liste[$j]->id,
                        'profession'=>$liste[$j]->profession,
                        'commentaire'=>$liste[$j]->commentaire,
                        'sexe'=>$liste[$j]->sexe,
                        'date_naiss'=>$liste[$j]->date_naiss,
                        'photo'=>$liste[$j]->photo,
                        'cin'=>$liste[$j]->cin,
                        'pays_id'=>$liste[$j]->pays_id,
                        'pays_desc'=>Pays::where('id',$liste[$j]->pays_id)->value('desc'),
                        'categ_instructeur_desc'=>CategInstructeur::where('id',$liste[$j]->categ_instructeur_id)->value('desc'),
                        'nom'=>User::where('instructeur_id',$liste[$j]->id)->value('nom'),
                        'prenom'=>User::where('instructeur_id',$liste[$j]->id)->value('prenom'),
                        'mail'=>User::where('instructeur_id',$liste[$j]->id)->value('mail'),
                        'adresse'=>User::where('instructeur_id',$liste[$j]->id)->value('adresse'),
                        'tel'=>User::where('instructeur_id',$liste[$j]->id)->value('tel'),
                        'email'=>User::where('instructeur_id',$liste[$j]->id)->value('email'),

                    ]);
            }
        }

        $nbr_instructeurs = Instructeur::count();
        $detail_cat = CategInstructeur::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $instructeurlist,
            'list_cat' => $list_cat,
            'nbr_instructeurs' => $nbr_instructeurs,
            'detail_cat' => $detail_cat,
            'list_pays' => $list_pays,
            "message" => '',
        ]);

    }
}
