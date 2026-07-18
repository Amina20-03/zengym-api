<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index_admin(){
        $adminlist =[];
        $liste = Admin::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($adminlist,
                    [
                        'id'=>$liste[$j]->id,
                        'nom'=>User::where('admin_id',$liste[$j]->id)->value('nom'),
                        'prenom'=>User::where('admin_id',$liste[$j]->id)->value('prenom'),
                        'mail'=>User::where('admin_id',$liste[$j]->id)->value('mail'),
                        'adresse'=>User::where('admin_id',$liste[$j]->id)->value('adresse'),
                        'tel'=>User::where('admin_id',$liste[$j]->id)->value('tel'),
                        'email'=>User::where('admin_id',$liste[$j]->id)->value('email'),

                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $adminlist,
            "message" => '',
        ]);
    }
    public function add_admin(Request $request){
        $status=false;
        $message='';
        if($request->mail != null){
            $verif = User::where('mail', $request->mail)->where('admin_id','!=',null)
                ->count();
            if($verif>0){
                $status=false;
                $message='content.Email_existe_déjà';

            }
            else{
                Admin::create([

                ]);
                User::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'mail'=>$request->mail,
                    'adresse'=>$request->adresse,
                    'tel'=>$request->tel,
                    'role'=>'ADMIN',
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password),
                    'admin_id'=>Admin::max('id'),
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
    public function edit_admin($id,Request $request){
        $detail_user = User::where('admin_id',$id)->get();
        return response()->json([
            "status" => true,
            "detail_user" => $detail_user,
            "message" => '',
        ]);
    }
    public function update_admin($id,Request $request){
        $status=false;
        $message='';
        if($request->mail_old != $request->mail){
            $verif = User::where('mail', $request->mail)->where('admin_id','!=',null)
                ->count();
            if($verif>0){
                $status = false;
                $message = 'content.Email_existe_déjà';

            }
            else{

                User::where('admin_id',$id)
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

            User::where('admin_id',$id)
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
    public function update_admin_password($id,Request $request){
        $status = false;
        $message = "";
        $ancienpassword = $request->ancienpassword;
        $password = $request->password;
        $conf_password = $request->conf_password;
        $user_password = User::where('admin_id',$id)
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
                    User::where('admin_id',$id)
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
    public function delete_admin(Request $request){
        $id = $request->champ_id;
        User::where('admin_id',$id)
            ->delete();
        Admin::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
