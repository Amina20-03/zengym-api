<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CategCours;
use App\Models\CategInstructeur;
use App\Models\Cours;
use App\Models\CoursPhotos;
use App\Models\CoursUser;
use App\Models\CoursVideos;
use App\Models\Instructeur;
use App\Models\Pays;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index_categ_cours(){
        $liste = CategCours::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_cours(Request $request){
        $max_id = CategCours::max('id')+1;
        $code = 'CC_00'.$max_id;
        CategCours::create([
            'code'=>$code,
            'titre'=>$request->titre,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_cours($id,Request $request){
        $detail = CategCours::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_cours($id,Request $request){
        CategCours::where('id',$id)
            ->update([
                'titre'=>$request->titre,
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_cours(Request $request){
        $id = $request->champ_id;
        Cours::where('categ_cours_id',$id)
            ->delete();
        CategCours::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_cours2(){
        $currentDate = Carbon::now();
        $courslist =[];
        $list_cat = CategCours::orderBy('id','desc')->get();
        $liste = Cours::where('approuver',true)->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'titre'=>$liste[$j]->titre,
                        'sujet'=>$liste[$j]->sujet,
                        'approuver'=>$liste[$j]->approuver,
                        'realiser'=>$liste[$j]->realiser,
                        'desc'=>$liste[$j]->desc,
                        'frais'=>$liste[$j]->frais,
                        'devise'=>$liste[$j]->devise,
                        'date'=>$liste[$j]->date,
                        'hdeb'=>$liste[$j]->hdeb,
                        'hfin'=>$liste[$j]->hfin,
                        'emplacement'=>$liste[$j]->emplacement,
                        'categ_cours_desc'=>CategCours::where('id',$liste[$j]->categ_cours_id)->value('titre'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>Instructeur::where('id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_user'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_categ'=>CategInstructeur::where('id',Instructeur::where('id',$liste[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),

                    ]);
            }
        }

        $nbr_cours = Cours::count();
        //$detail_cat = CategFormation::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $courslist,
            'list_cat' => $list_cat,
            'nbr_formations' => $nbr_cours,
            //   'detail_cat' => $detail_cat,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
            "message" => '',
        ]);

    }
    public function index_cours(){
        $currentDate = Carbon::now();
        $liste_cat = CategCours::orderBy('id','desc')->get();
        $courslist =[];
        $id_categ = $liste_cat[0]->id;
        $liste = Cours::where('categ_cours_id',$id_categ)->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'frais'=>$liste[$j]->frais,
                        'devise'=>$liste[$j]->devise,
                        'date'=>$liste[$j]->date,
                        'hdeb'=>$liste[$j]->hdeb,
                        'hfin'=>$liste[$j]->hfin,
                        'emplacement'=>$liste[$j]->emplacement,
                        'categ_cours_desc'=>CategCours::where('id',$liste[$j]->categ_cours_id)->value('titre'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>Instructeur::where('id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_user'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_categ'=>CategInstructeur::where('id',Instructeur::where('id',$liste[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),


                    ]);
            }
        }
        $nbr_cours = Cours::count();
        $detail_cat = CategCours::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste_cat' => $liste_cat,
            'liste' => $courslist,
            'nbr_cours' => $nbr_cours,
            'detail_cat' => $detail_cat,
            "message" => '',
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
        ]);
    }
    public function index_cours_by_categ($id_categ){
        $currentDate = Carbon::now();
        $liste_cat = CategCours::orderBy('id','desc')->get();
        $courslist =[];
        $liste = Cours::where('categ_cours_id',$id_categ)->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'frais'=>$liste[$j]->frais,
                        'date'=>$liste[$j]->date,
                        'hdeb'=>$liste[$j]->hdeb,
                        'hfin'=>$liste[$j]->hfin,
                        'emplacement'=>$liste[$j]->emplacement,
                        'categ_cours_desc'=>CategCours::where('id',$liste[$j]->categ_cours_id)->value('titre'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>Instructeur::where('id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_user'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_categ'=>CategInstructeur::where('id',Instructeur::where('id',$liste[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),


                    ]);
            }
        }
        $nbr_cours = Cours::count();
        $detail_cat = CategCours::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste_cat' => $liste_cat,
            'liste' => $courslist,
            'nbr_cours' => $nbr_cours,
            'detail_cat' => $detail_cat,
            "message" => '',
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
        ]);
    }
    public function search_cours(Request $request){
        $id_categ = $request->id_categ;
        $date_deb = $request->du_date;

        $cur_time   =   strtotime(now());

        $heure_deb = null;
        $hdeb_time    =   strtotime($request->heure_deb);
        if($hdeb_time != $cur_time){
            $heure_deb = $request->heure_deb;
        }
        $heure_fin = null;
        $hfin_time    =   strtotime($request->heure_fin);
        if($hfin_time != $cur_time){
            $heure_fin = $request->heure_fin;
        }

        $currentDate = Carbon::now();
        $liste_cat = CategCours::orderBy('id','desc')->get();
        $courslist =[];

        if($hdeb_time != $cur_time){
            $liste = Cours::where('categ_cours_id',$id_categ)->where('date',$date_deb)->where('hdeb',$heure_deb)->orderBy('date','desc')->get();
        }
        if($hfin_time != $cur_time){
            $liste = Cours::where('categ_cours_id',$id_categ)->where('date',$date_deb)->where('hfin',$heure_fin)->orderBy('date','desc')->get();
        }
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'frais'=>$liste[$j]->frais,
                        'date'=>$liste[$j]->date,
                        'hdeb'=>$liste[$j]->hdeb,
                        'hfin'=>$liste[$j]->hfin,
                        'categ_cours_desc'=>CategCours::where('id',$liste[$j]->categ_cours_id)->value('titre'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>Instructeur::where('id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_user'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_categ'=>CategInstructeur::where('id',Instructeur::where('id',$liste[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),


                    ]);
            }
        }
        $nbr_cours = Cours::count();
        $detail_cat = CategCours::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste_cat' => $liste_cat,
            'liste' => $courslist,
            'nbr_cours' => $nbr_cours,
            'detail_cat' => $detail_cat,
            "message" => '',
            'du_date' => $date_deb,
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => $heure_deb,
            'heure_fin' => $heure_fin,
        ]);
    }
    public function create_dmd_cours(Request $request){
        $list_cat = CategCours::all();
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function create_cours(Request $request){
        $list_cat = CategCours::all();
        $instructeurlist =[];
        $liste = Instructeur::orderBy('id','desc')->get();
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
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'list_instructeurs' => $instructeurlist,
            "message" => '',
        ]);
    }
    public function add_cours(Request $request){

        $max_id = Cours::max('id')+1;
        $code = 'C_00'.$max_id;
        Cours::create([
            'code'=>$code,
            'titre'=>$request->titre,
            'desc'=>$request->desc,
            'emplacement'=>$request->emplacement,
            'devise'=>$request->devise,
            'date'=>$request->date,
            'hdeb'=>$request->hdeb,
            'hfin'=>$request->hfin,
            'sujet'=>$request->sujet,
            'frais'=>$request->frais,
            'categ_cours_id'=>$request->categ_cours_id,
            'user_id'=>auth()->user()->id,
            'instructeur_id'=>$request->instructeur_id,
            'organisateur_id'=>$request->organisateur_id,
            'approuver'=>$request->approuver,
            'realiser'=>$request->realiser,
        ]);
        $list_instr_id_list = explode("|", $request->instr_id_list);
        if ($list_instr_id_list != null) {
            if (count($list_instr_id_list) > 1) {
                for ($i=0;$i<count($list_instr_id_list);$i++) {
                    if ($list_instr_id_list[$i] != "") {
                        CoursUser::create([
                            'date_validation'=>date('Y-m-d'),
                            'cours_id'=>Cours::max('id'),
                            'user_id'=>User::where('instructeur_id',$list_instr_id_list[$i])->value('id') ,
                        ]);

                    }
                }
            }

        }
        if($request->data_photos != null){
            foreach ($request->data_photos as $data) {
                CoursPhotos::create([
                    'photo'=>$data['photo'],
                    'titre'=>$data['titre'],
                    'desc'=>$data['desc'],
                    'cours_id'=>Cours::max('id'),
                ]);

            }
        }

        if($request->data_videos != null){
            foreach ($request->data_videos as $data) {
                CoursVideos::create([
                    'path'=>$data['path'],
                    'titre'=>$data['titre'],
                    'desc'=>$data['desc'],
                    'cours_id'=>Cours::max('id'),
                ]);

            }
        }

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_dmd_cours(){
        $courslist =[];
        $liste = Cours::where(function($q){ $q->where('approuver',false)->orWhereNull('approuver'); })->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($courslist,
                    [
                       'sujet'=>$liste[$j]->sujet,
                       'titre'=>$liste[$j]->titre,
                       'approuver'=>$liste[$j]->approuver,
                       'devise'=>$liste[$j]->devise,
                        'enligne'=>$liste[$j]->enligne,
                        'realiser'=>$liste[$j]->realiser,


                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'frais'=>$liste[$j]->frais,
                        'date'=>$liste[$j]->date,
                        'hdeb'=>$liste[$j]->hdeb,
                        'hfin'=>$liste[$j]->hfin,
                        'emplacement'=>$liste[$j]->emplacement,
                        'categ_cours_desc'=>CategCours::where('id',$liste[$j]->categ_cours_id)->value('titre'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>Instructeur::where('id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_user'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->get(),
                        'instructeur_categ'=>CategInstructeur::where('id',Instructeur::where('id',$liste[$j]->instructeur_id)->value('categ_instructeur_id'))->value('desc'),

                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $courslist,
            "message" => '',
        ]);

    }
    public function realiser_cours($id,Request $request){
        Cours::where('id',$id)
            ->update([
                'realiser'=>$request->realiser,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_cours(Request $request){
        $id = $request->champ_id;
        CoursPhotos::where('cours_id',$id)
            ->delete();
        CoursVideos::where('cours_id',$id)
            ->delete();
        CoursUser::where('cours_id',$id)
            ->delete();
        Cours::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_cours($id,Request $request){
        $detail = Cours::where('id',$id)->get();
        $list_cat = CategCours::all();
        $cat = CategCours::where('id',$detail[0]->categ_cours_id)->value('titre');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_cat" => $list_cat,
            "cat" => $cat,
            "message" => '',
        ]);
    }
    public function update_cours($id,Request $request){
        Cours::where('id',$id)
            ->update([
                'titre'=>$request->titre,
                'desc'=>$request->desc,
                'emplacement'=>$request->emplacement,
                'devise'=>$request->devise,
                'date'=>$request->date,
                'hdeb'=>$request->hdeb,
                'hfin'=>$request->hfin,
                'sujet'=>$request->sujet,
                'frais'=>$request->frais,
                'categ_cours_id'=>$request->categ_cours_id,
                'instructeur_id'=>$request->instructeur_id,
                'approuver'=>$request->approuver,
                'realiser'=>$request->realiser,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function detail_cours($id){
        $detail = Cours::where('id',$id)->get();
        $list_cat = CategCours::all();
        $detail_cat = CategCours::where('id',$detail[0]->categ_cours_id)->get();
        $instructeur = User::where('id',$detail[0]->user_id)->value('nom').' '.User::where('id',$detail[0]->user_id)->value('prenom');
        if($detail[0]->instructeur_id){
            $detail_instructeur = Instructeur::where('id',$detail[0]->instructeur_id)->get();
            $categ_instructeur = CategInstructeur::where('id',$detail_instructeur[0]->categ_instructeur_id)->get();
        }
        else{
            $detail_instructeur= null;
            $categ_instructeur= null;
        }



        $list_candidats_cours = CoursUser::where('cours_id',$id)->get();
        $candidatlist =[];
        $liste_can = User::orderBy('id','desc')->get();
        if (count($list_candidats_cours)>0) {
            for ($i=0; $i <count($list_candidats_cours) ; $i++) {
                $liste = User::where('id',$list_candidats_cours[$i]->user_id)->get();

                if (count($liste)>0) {
                    for ($j=0; $j <count($liste) ; $j++) {
                        array_push($candidatlist,
                            [
                                'id'=>$liste[$j]->id,
                                'nom'=>$liste[$j]->nom,
                                'prenom'=>$liste[$j]->prenom,
                                'tel'=>$liste[$j]->tel,
                                'mail'=>$liste[$j]->mail,
                                'adresse'=>$liste[$j]->adresse,

                                'instructeur_id'=>$liste[$j]->instructeur_id,
                                'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                            ]);
                    }
                }
            }
        }
        $videos_list = CoursVideos::where('cours_id',$id)->get();
        $photos_list = CoursPhotos::where('cours_id',$id)->get();

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_cat" => $list_cat,
            "detail_cat" => $detail_cat,
            "instructeur" => $instructeur,
            "detail_instructeur" => $detail_instructeur,
            "categ_instructeur" => $categ_instructeur,
            "candidatlist" => $candidatlist,
            "liste_can" => $liste_can,
            "videos_list" => $videos_list,
            "photos_list" => $photos_list,
            "message" => '',
        ]);
    }
    public function refuser_dmd_cours($id){
        Cours::where('id',$id)
            ->update([
                'approuver'=>false,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function aprouver_dmd_cours($id){
        Cours::where('id',$id)
            ->update([
                'approuver'=>true,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function affecter_candidat(Request $request){
        $verif= CoursUser::where('cours_id',$request->cours_id)->where('user_id',User::where('candidat_id',$request->candidat_id)->value('id'))->count();
        if($verif==0){
            CoursUser::create([
                'date_validation'=>$request->date_validation,
                'formation_id'=>$request->formation_id,
                'user_id'=>User::where('candidat_id',$request->candidat_id)->value('id'),

            ]);
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);


    }
    public function delete_affect_candidat(Request $request){
        $id = $request->champ_id;
        $user_id = User::where('candidat_id',$id)->value('id');
        CoursUser::where('user_id',$user_id)
            ->delete();

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
