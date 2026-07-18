<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\AbonnementUser;
use App\Models\VenteAbo;
use App\Models\CategCours;
use App\Models\CategInstructeur;
use App\Models\Cours;
use App\Models\DocumentInstructeurCategorie;
use App\Models\Evenement;
use App\Models\Formation;
use App\Models\Instructeur;
use App\Models\Pays;
use App\Models\PhotoInstructeurCategorie;
use App\Models\TypeAbo;
use App\Models\User;
use App\Models\UserDocuments;
use App\Models\UserPhotos;
use App\Models\UserPhVidDocCategorie;
use App\Models\UserVideos;
use App\Models\VideoInstructeurCategorie;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index_profile(){
        $instructeur =[];
        $detail = Instructeur::where('id',auth()->user()->instructeur_id)
            ->get();
        $detail_user=User::where('instructeur_id',auth()->user()->instructeur_id)->get();
        array_push($instructeur,
            [
                'abonnements'=>AbonnementUser::where('user_id',$detail_user[0]->id)->get(),
                'id'=>auth()->user()->instructeur_id,
                'profession'=>$detail[0]->profession,
                'commentaire'=>$detail[0]->commentaire,
                'sexe'=>$detail[0]->sexe,
                'date_naiss'=>$detail[0]->date_naiss,
                'photo'=>$detail[0]->photo,
                'cin'=>$detail[0]->cin,
                'pays_desc'=>Pays::where('id',$detail[0]->pays_id)->value('desc'),
                'pays_id'=>$detail[0]->pays_id,
                'categ_instructeur_desc'=>CategInstructeur::where('id',$detail[0]->categ_instructeur_id)->value('desc'),
                'categ_instructeur_id'=>$detail[0]->categ_instructeur_id,
                'nom'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('nom'),
                'prenom'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('prenom'),
                'mail'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('mail'),
                'adresse'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('adresse'),
                'tel'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('tel'),
                'email'=>User::where('instructeur_id',auth()->user()->instructeur_id)->value('email'),


            ]);
        $courslist =[];
        $nbr_cours = Cours::where('instructeur_id',auth()->user()->instructeur_id)->get();
        if (count($nbr_cours)>0) {
            for ($j=0; $j <count($nbr_cours) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$nbr_cours[$j]->id,
                        'code'=>$nbr_cours[$j]->code,
                        'desc'=>$nbr_cours[$j]->desc,
                        'frais'=>$nbr_cours[$j]->frais,
                        'date'=>$nbr_cours[$j]->date,
                        'hdeb'=>$nbr_cours[$j]->hdeb,
                        'hfin'=>$nbr_cours[$j]->hfin,
                        'categ_cours_id'=>$nbr_cours[$j]->categ_cours_id,
                        'categ_cours_desc'=>CategCours::where('id',$nbr_cours[$j]->categ_cours_id)->value('desc'),
                    ]);
            }
        }

        $nbr_Evennement = Evenement::where('instructeur_id',auth()->user()->instructeur_id)->get();

        $list_pays = Pays::all();
        $list_cat = CategInstructeur::all();
        $type_abonnements = TypeAbo::all();
        return response()->json([
            "status" => true,
            'instructeur' => $instructeur,
            'nbr_cours' => $nbr_cours,
            'courslist' => $courslist,
            'nbr_Evennement' => $nbr_Evennement,

            'list_pays' => $list_pays,
            'type_abonnements' => $type_abonnements,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function index_profile_by_id($id_instr){
        $instructeur =[];
        $detail = Instructeur::where('id',$id_instr)->get();
        array_push($instructeur,
            [
                'id'=>$id_instr,
                'profession'=>$detail[0]->profession,
                'commentaire'=>$detail[0]->commentaire,
                'sexe'=>$detail[0]->sexe,
                'date_naiss'=>$detail[0]->date_naiss,
                'photo'=>$detail[0]->photo,
                'cin'=>$detail[0]->cin,
                'pays_desc'=>Pays::where('id',$detail[0]->pays_id)->value('desc'),
                'pays_id'=>$detail[0]->pays_id,
                'categ_instructeur_desc'=>CategInstructeur::where('id',$detail[0]->categ_instructeur_id)->value('desc'),
                'categ_instructeur_id'=>$detail[0]->categ_instructeur_id,
                'nom'=>User::where('instructeur_id',$id_instr)->value('nom'),
                'prenom'=>User::where('instructeur_id',$id_instr)->value('prenom'),
                'mail'=>User::where('instructeur_id',$id_instr)->value('mail'),
                'adresse'=>User::where('instructeur_id',$id_instr)->value('adresse'),
                'tel'=>User::where('instructeur_id',$id_instr)->value('tel'),
                'email'=>User::where('instructeur_id',$id_instr)->value('email'),


            ]);
        $courslist =[];
        $nbr_cours = Cours::where('instructeur_id',$id_instr)->get();
        if (count($nbr_cours)>0) {
            for ($j=0; $j <count($nbr_cours) ; $j++) {
                array_push($courslist,
                    [
                        'id'=>$nbr_cours[$j]->id,
                        'code'=>$nbr_cours[$j]->code,
                        'desc'=>$nbr_cours[$j]->desc,
                        'frais'=>$nbr_cours[$j]->frais,
                        'date'=>$nbr_cours[$j]->date,
                        'hdeb'=>$nbr_cours[$j]->hdeb,
                        'hfin'=>$nbr_cours[$j]->hfin,
                        'categ_cours_id'=>$nbr_cours[$j]->categ_cours_id,
                        'categ_cours_desc'=>CategCours::where('id',$nbr_cours[$j]->categ_cours_id)->value('desc'),
                    ]);
            }
        }

        $nbr_Evennement = Evenement::where('instructeur_id',$id_instr)->get();

        $list_pays = Pays::all();
        $list_cat = CategInstructeur::all();

        return response()->json([
            "status" => true,
            'instructeur' => $instructeur,
            'nbr_cours' => $nbr_cours,
            'courslist' => $courslist,
            'nbr_Evennement' => $nbr_Evennement,

            'list_pays' => $list_pays,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function index_photos(){
        $list_cat = PhotoInstructeurCategorie::all();
        $nbr_photos = UserPhotos::where('user_id',auth()->user()->id)->count();

        $liste_photos =[];
        $liste1 = UserPhotos::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_photos,
                    [
                        'id'=>$liste1[$j]->id,
                        'photo'=>$liste1[$j]->photo,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $detail_cat = [];
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_photos' => $nbr_photos,
            'liste_photos' => $liste_photos,
            'detail_cat' => $detail_cat,
            "message" => '',
        ]);
    }
    public function index_photos_by_id_instr($id_instr){
        $list_cat = PhotoInstructeurCategorie::all();
        $nbr_photos = UserPhotos::where('user_id',$id_instr)->count();

        $liste_photos =[];
        $liste1 = UserPhotos::where('user_id',$id_instr)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_photos,
                    [
                        'id'=>$liste1[$j]->id,
                        'photo'=>$liste1[$j]->photo,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $instructeur_id = $id_instr;
        $detail_cat = [];
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_photos' => $nbr_photos,
            'liste_photos' => $liste_photos,
            'detail_cat' => $detail_cat,
            'instructeur_id' => $instructeur_id,
            "message" => '',
        ]);
    }
    public function add_categ(Request $request){

        PhotoInstructeurCategorie::create([
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_categ_video(Request $request){

        VideoInstructeurCategorie::create([
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_categ_document(Request $request){

        DocumentInstructeurCategorie::create([
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function add_photos(Request $request){
        foreach ($request->data_photos as $data) {
            UserPhotos::create([
                'photo'=>$data['photo'],
                'titre'=>$data['titre'],
                'desc'=>$data['desc'],
                'categ_id'=>$data['categ_id'],
                'user_id'=>auth()->user()->id,
            ]);

        }

        return response()->json([
            "status" => true,
            "message" => '',

        ]);
    }
    public function delete_photo(Request $request){
        $id = $request->champ_id;

        UserPhotos::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function search_photos(Request $request){
        $id_categ = $request->id_categ_searching;
        $list_cat = PhotoInstructeurCategorie::all();
        $detail_cat = PhotoInstructeurCategorie::where('id',$id_categ)->get();
        $nbr_photos = UserPhotos::where('user_id',auth()->user()->id)->count();

        $liste_photos =[];
        $liste1 = UserPhotos::where('user_id',auth()->user()->id)->where('categ_id',$id_categ)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_photos,
                    [
                        'id'=>$liste1[$j]->id,
                        'photo'=>$liste1[$j]->photo,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>PhotoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_photos' => $nbr_photos,
            'liste_photos' => $liste_photos,
            'detail_cat' => $detail_cat,
            "message" => '',
        ]);
    }
    public function index_videos(){
        $list_cat = VideoInstructeurCategorie::all();
        $nbr_videos = UserVideos::where('user_id',auth()->user()->id)->count();

        $liste_videos =[];
        $liste1 = UserVideos::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_videos,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $detail_cat = [];
        $detail = Instructeur::where('id',auth()->user()->instructeur_id)
            ->get();
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_videos' => $nbr_videos,
            'liste_videos' => $liste_videos,
            'detail_cat' => $detail_cat,
            'detail' => $detail,
            "message" => '',
        ]);
    }
    public function index_videos_by_id_instr($id_instr){
        $list_cat = VideoInstructeurCategorie::all();
        $nbr_videos = UserVideos::where('user_id',$id_instr)->count();

        $liste_videos =[];
        $liste1 = UserVideos::where('user_id',$id_instr)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_videos,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $detail_cat = [];
        $instructeur_id = $id_instr;
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_videos' => $nbr_videos,
            'liste_videos' => $liste_videos,
            'detail_cat' => $detail_cat,
            'instructeur_id' => $instructeur_id,
            "message" => '',
        ]);
    }
    public function delete_video(Request $request){
        $id = $request->champ_id;

        UserVideos::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_videos(Request $request){
        foreach ($request->data_videos as $data) {
            UserVideos::create([
                'path'=>$data['path'],
                'titre'=>$data['titre'],
                'desc'=>$data['desc'],
                'categ_id'=>$data['categ_id'],
                'user_id'=>auth()->user()->id,
            ]);

        }

        return response()->json([
            "status" => true,
            "message" => '',

        ]);
    }
    public function search_videos(Request $request){
        $id_categ = $request->id_categ_searching;
        $list_cat = VideoInstructeurCategorie::all();
        $detail_cat = VideoInstructeurCategorie::where('id',$id_categ)->get();
        $nbr_videos = UserVideos::where('user_id',auth()->user()->id)->count();

        $liste_videos =[];
        $liste1 = UserVideos::where('user_id',auth()->user()->id)->where('categ_id',$id_categ)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_videos,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>VideoInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_videos' => $nbr_videos,
            'liste_videos' => $liste_videos,
            'detail_cat' => $detail_cat,
            "message" => '',
        ]);
    }
    public function index_playlist(){

        $detail = Instructeur::where('id',auth()->user()->instructeur_id)
            ->get();
        return response()->json([
            "status" => true,

            'detail' => $detail,
            "message" => '',
        ]);
    }
    public function index_docs(){
        $list_cat = DocumentInstructeurCategorie::all();
        $nbr_docs = UserDocuments::where('user_id',auth()->user()->id)->count();

        $liste_docs =[];
        $liste1 = UserDocuments::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_docs,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $detail_cat = [];
        $detail = Instructeur::where('id',auth()->user()->instructeur_id)
            ->get();
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_docs' => $nbr_docs,
            'liste_docs' => $liste_docs,
            'detail_cat' => $detail_cat,
            'detail' => $detail,
            "message" => '',
        ]);
    }
    public function index_docs_by_id_instr($id_instr){
        $list_cat = DocumentInstructeurCategorie::all();
        $nbr_docs = UserDocuments::where('user_id',$id_instr)->count();

        $liste_docs =[];
        $liste1 = UserDocuments::where('user_id',$id_instr)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_docs,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        $detail_cat = [];
        $instructeur_id = $id_instr;
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_docs' => $nbr_docs,
            'liste_docs' => $liste_docs,
            'detail_cat' => $detail_cat,
            'instructeur_id' => $instructeur_id,
            "message" => '',
        ]);
    }
    public function delete_doc(Request $request){
        $id = $request->champ_id;

        UserDocuments::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_docs(Request $request){
        foreach ($request->data_docs as $data) {
            UserDocuments::create([
                'path'=>$data['path'],
                'titre'=>$data['titre'],
                'desc'=>$data['desc'],
                'categ_id'=>$data['categ_id'],
                'user_id'=>auth()->user()->id,
            ]);

        }

        return response()->json([
            "status" => true,
            "message" => '',

        ]);
    }
    public function search_docs(Request $request){
        $id_categ = $request->id_categ_searching;
        $list_cat = DocumentInstructeurCategorie::all();
        $detail_cat = DocumentInstructeurCategorie::where('id',$id_categ)->get();
        $nbr_docs = UserDocuments::where('user_id',auth()->user()->id)->count();

        $liste_docs =[];
        $liste1 = UserDocuments::where('user_id',auth()->user()->id)->where('categ_id',$id_categ)->orderBy('id','desc')->get();
        if (count($liste1)>0) {
            for ($j=0; $j <count($liste1) ; $j++) {
                array_push($liste_docs,
                    [
                        'id'=>$liste1[$j]->id,
                        'path'=>$liste1[$j]->path,
                        'titre'=>$liste1[$j]->titre,
                        'desc'=>$liste1[$j]->desc,
                        'categ_code'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('code'),
                        'categ_desc'=>DocumentInstructeurCategorie::where('id',$liste1[$j]->categ_id)->value('desc'),
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            'nbr_docs' => $nbr_docs,
            'liste_docs' => $liste_docs,
            'detail_cat' => $detail_cat,
            "message" => '',
        ]);
    }
    public function detailsDocument($id,Request $request){
        $detail_doc = UserDocuments::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail_doc" => $detail_doc,
            "message" => '',
        ]);
    }
    public function payer_abonnement(Request $request){
        $newDate = date('Y-m-d', strtotime('+1 month'));
        $active=false;
        if($request->paiement_status){
            $active=true;
            AbonnementUser::where('user_id',$request->user_id)
                ->update([
                    'active'=>false,
                ]);
            VenteAbo::where('instructeur_id',$request->instructeur_id)
                ->update([
                    'dernier'=>false,
                ]);
        }

        AbonnementUser::create([
            'titre'=>$request->type_abo_desc,
            'date_paie'=>$request->date_validation,
            'ref'=>$request->ref,
            'status_paie'=>$request->paiement_status,
            'type_paie'=>$request->methode_paiement,
            'type_abo_id'=>$request->type_abo_id,
            'date_deb'=>date('Y-m-d'),
            'date_fin'=>$newDate,
            'active'=>$active,
            'user_id'=>$request->user_id,

        ]);

        $max_id = VenteAbo::max('id');
        $code = 'VA_00'.$max_id;
        $vente_abo = VenteAbo::create([
            'code'=>$code,
            'date_paie'=>$request->date_validation,
            'montant_ttc'=>$request->amount,
            'paiement'=>$request->amount,
            'solder'=>$request->paiement_status,
            'date_deb'=>date('Y-m-d'),
            'date_fin'=>$newDate,
            'type_abo_id'=>4,
            'instructeur_id'=>$request->instructeur_id,
            'dernier'=>$active,
            'created_at' => now(),
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
}
