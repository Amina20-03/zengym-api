<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Mail\ZenGymConfirmationEmail;
use App\Models\AbonnementUser;
use App\Models\Candidat;
use App\Models\Cart;
use App\Models\CategCandidat;
use App\Models\CategFormation;
use App\Models\CategInstructeur;
use App\Models\Compte;
use App\Models\Cours;
use App\Models\Formation;
use App\Models\FormationAudio;
use App\Models\FormationCandidat;
use App\Models\FormationDocument;
use App\Models\FormationPhotos;
use App\Models\FormationUser;
use App\Models\FormationVideos;
use App\Models\Instructeur;
use App\Models\LangueFormation;
use App\Models\Operation;
use App\Models\Pays;
use App\Models\Pourcentage;
use App\Models\SalleDeSport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FormationController extends Controller
{
    public function index_categ_formation(){
        $liste = CategFormation::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_formation(Request $request){
        $max_id = CategFormation::max('id')+1;
        $code = 'CF_00'.$max_id;
        CategFormation::create([
            'code'=>$code,
            'lib'=>$request->lib,
            'duree'=>$request->duree,
            'desc'=>$request->desc,
            'ordre'=>$max_id,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_formation($id,Request $request){
        $detail = CategFormation::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_formation($id,Request $request){
        CategFormation::where('id',$id)
            ->update([
                'lib'=>$request->lib,
                'duree'=>$request->duree,
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_formation(Request $request){
        $id = $request->champ_id;
        Formation::where('categ_formation_id',$id)
            ->delete();
        CategFormation::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_formation(){
        $currentDate = Carbon::now();
        $formationlist =[];
        $list_cat = CategFormation::orderBy('id','desc')->get();
        $liste = Formation::where('approuver',true)->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($formationlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'date'=>$liste[$j]->date,
                        'heure'=>$liste[$j]->heure,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'sujet'=>$liste[$j]->sujet,
                        'frais'=>$liste[$j]->frais,
                        'nbr_place_max'=>$liste[$j]->nbr_place_max,
                        'categ_formation_desc'=>CategFormation::where('id',$liste[$j]->categ_formation_id)->value('lib'),
                        'categ_formation_id'=>$liste[$j]->categ_formation_id,
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                        'approuver'=>$liste[$j]->approuver,
                        'encaisse'=>$liste[$j]->encaisse,
                        'realiser'=>$liste[$j]->realiser,
                        'enligne'=>$liste[$j]->enligne,
                    ]);
            }
        }

        $nbr_formations = Formation::count();
        //$detail_cat = CategFormation::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $formationlist,
            'list_cat' => $list_cat,
            'nbr_formations' => $nbr_formations,
            //   'detail_cat' => $detail_cat,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
            "message" => '',
        ]);

    }
    public function candidat_index_formation($user_id){
        $currentDate = Carbon::now();
        $formationlist =[];
        $list_cat = CategFormation::orderBy('id','desc')->get();
        $liste = FormationCandidat::where('user_id',$user_id)->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($formationlist,
                    [
                        'id'=>$liste[$j]->id,
                        'id_form'=>Formation::where('id',$liste[$j]->formation_id)->value('id') ,
                        'code'=>Formation::where('id',$liste[$j]->formation_id)->value('code'),
                        'date'=>Formation::where('id',$liste[$j]->formation_id)->value('date'),
                        'heure'=>Formation::where('id',$liste[$j]->formation_id)->value('heure'),
                        'nbr_participant'=>Formation::where('id',$liste[$j]->formation_id)->value('nbr_participant'),
                        'sujet'=>Formation::where('id',$liste[$j]->formation_id)->value('sujet'),
                        'frais'=>Formation::where('id',$liste[$j]->formation_id)->value('frais'),
                        'nbr_place_max'=>Formation::where('id',$liste[$j]->formation_id)->value('nbr_place_max'),
                        'categ_formation_desc'=>CategFormation::where('id',Formation::where('id',$liste[$j]->formation_id)->value('categ_formation_id'))->value('lib'),
                        'candidat'=>User::where('id',$liste[$j]->user_id)->value('nom').' '.User::where('id',$liste[$j]->user_id)->value('prenom'),
                        'approuver'=>Formation::where('id',$liste[$j]->formation_id)->value('approuver'),
                        'realiser'=>Formation::where('id',$liste[$j]->formation_id)->value('realiser'),
                        'enligne'=>Formation::where('id',$liste[$j]->formation_id)->value('enligne'),
                        'date_validation'=>$liste[$j]->date_validation,
                        'methode_paiement'=>$liste[$j]->methode_paiement,
                    ]);
            }
        }

        $nbr_formations = Formation::count();
        //$detail_cat = CategFormation::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $formationlist,
            'list_cat' => $list_cat,
            'nbr_formations' => $nbr_formations,
            //   'detail_cat' => $detail_cat,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
            "message" => '',
        ]);

    }
    public function candidat_detail_formation($id_formation)
    {
        $formation = Formation::where('id',$id_formation)
            ->with('photos','documents','videos','audios')
            ->first();
        return response()->json([
            "status" => true,
            'formation' => $formation,
            "message" => '',
        ]);
    }
    public function index_formation_by_categ($id_categ){
        $currentDate = Carbon::now();
        $formationlist =[];
        $list_cat = CategFormation::orderBy('id','desc')->get();
        if ($id_categ != 'all'){
            $liste = Formation::where('approuver',true)
                ->where('categ_formation_id',$id_categ)
                ->orderBy('date','desc')
                ->get();
        }
        else{
            if(count($list_cat)>0){
                $id_categ = $list_cat[0]->id;
                $liste = Formation::where('approuver',true)
                    ->where('categ_formation_id',$id_categ)
                    ->orderBy('date','desc')

                    ->get();
            }
            //$liste = [];
        }


        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($formationlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'date'=>$liste[$j]->date,
                        'heure'=>$liste[$j]->heure,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'sujet'=>$liste[$j]->sujet,
                        'frais'=>$liste[$j]->frais,
                        'nbr_place_max'=>$liste[$j]->nbr_place_max,
                        'categ_formation_desc'=>CategFormation::where('id',$liste[$j]->categ_formation_id)->value('lib'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                        'approuver'=>$liste[$j]->approuver,
                        'realiser'=>$liste[$j]->realiser,
                        'enligne'=>$liste[$j]->enligne,
                    ]);
            }
        }

        $nbr_formations = Formation::count();
        $detail_cat = CategFormation::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $formationlist,
            'list_cat' => $list_cat,
            'nbr_formations' => $nbr_formations,
            'detail_cat' => $detail_cat,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
            "message" => '',
        ]);

    }
    public function index_dmd_formation(){
        $formationlist =[];
        $liste = Formation::where('approuver',false)->orderBy('date','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($formationlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'date'=>$liste[$j]->date,
                        'heure'=>$liste[$j]->heure,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'sujet'=>$liste[$j]->sujet,
                        'frais'=>$liste[$j]->frais,
                        'nbr_place_max'=>$liste[$j]->nbr_place_max,
                        'categ_formation_desc'=>CategFormation::where('id',$liste[$j]->categ_formation_id)->value('lib'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                        'approuver'=>$liste[$j]->approuver,
                        'enligne'=>$liste[$j]->enligne,
                        'realiser'=>$liste[$j]->realiser,
                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $formationlist,
            "message" => '',
        ]);

    }
    public function create_dmd_formation(Request $request){
        $list_cat = CategFormation::all();
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function create_formation(Request $request){
        $list_cat = CategFormation::all();
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
    public function add_formation(Request $request){



        $max_id = Formation::max('id')+1;
        $code = 'F_00'.$max_id;
        Formation::create([
            'code'=>$code,
            'titre'=>$request->titre,
            'desc'=>$request->desc,
            'lieu'=>$request->lieu,
            'contact'=>$request->contact,
            'devise'=>$request->devise,
            'date'=>$request->date,
            'heure'=>$request->heure,
            'nbr_participant'=>$request->nbr_participant,
            'sujet'=>$request->sujet,
            'frais'=>$request->frais,
            'nbr_place_max'=>$request->nbr_place_max,
            'categ_formation_id'=>$request->categ_formation_id,
            'approuver'=>$request->approuver,
            'realiser'=>$request->realiser,
            'enligne'=>$request->enligne,
            'user_id'=>auth()->user()->id,
            'instructeur_id'=>$request->instructeur_id,
            'organisateur_id'=>$request->organisateur_id,
            'photo_principale'=>$request->photo_principale,
            'path_livret_scientifique' => $request->path_livret_scientifique,
            'path_presentation_power_point' => $request->path_presentation_power_point, // Send the array of photos
            'path_prog_de_formation' => $request->path_prog_de_formation, // Send the array of photos
            'path_video_basicone' => $request->path_video_basicone, // Send the array of photos

        ]);
        FormationVideos::create([
            'path'=>$request->path_video_basicone,
            'formation_id'=>Formation::max('id'),
        ]);
        FormationDocument::create([
            'titre'=>'livret_scientifique',
            'path'=>$request->path_livret_scientifique,
            'formation_id'=>Formation::max('id'),
        ]);
        FormationDocument::create([
            'titre'=>'prog_de_formation',
            'path'=>$request->path_prog_de_formation,
            'formation_id'=>Formation::max('id'),
        ]);
        FormationDocument::create([
            'titre'=>'presentation_power_point',
            'path'=>$request->path_presentation_power_point,
            'formation_id'=>Formation::max('id'),
        ]);
        $liste_lang = $request->langue;
        if(count($liste_lang)>0){
            for ($i=0;$i<count($liste_lang);$i++){
                LangueFormation::create([
                    'langue'=>$liste_lang[$i],
                    'formation_id'=>Formation::max('id'),
                ]);
            }
        }
        $list_instr_id_list = explode("|", $request->instr_id_list);
        if ($list_instr_id_list != null) {
            if (count($list_instr_id_list) > 1) {
                for ($i=0;$i<count($list_instr_id_list);$i++) {
                    if ($list_instr_id_list[$i] != "") {
                        FormationUser::create([
                            'date_validation'=>date('Y-m-d'),
                            'formation_id'=>Formation::max('id'),
                            'user_id'=>User::where('instructeur_id',$list_instr_id_list[$i])->value('id') ,
                        ]);

                    }
                }
            }

        }
//        if($request->photos){
//            foreach ($request->photos as $file) {
//                FormationPhotos::create([
//                    'photo'=>$file,
//                    'formation_id'=>Formation::max('id'),
//                ]);
//            }
//        }
//        if($request->videos){
//            foreach ($request->videos as $file) {
//                FormationVideos::create([
//                    'path'=>$file,
//                    'formation_id'=>Formation::max('id'),
//                ]);
//            }
//        }
//        if($request->docs){
//            foreach ($request->docs as $file) {
//                FormationDocument::create([
//                    'path'=>$file,
//                    'formation_id'=>Formation::max('id'),
//                ]);
//            }
//        }
//        if($request->audios){
//            foreach ($request->audios as $file) {
//                FormationAudio::create([
//                    'path'=>$file,
//                    'formation_id'=>Formation::max('id'),
//                ]);
//            }
//        }

//        if($request->data_photos != null){
//            foreach ($request->data_photos as $data) {
//                FormationPhotos::create([
//                    'photo'=>$data['photo'],
//                    'titre'=>$data['titre'],
//                    'desc'=>$data['desc'],
//                    'formation_id'=>Formation::max('id'),
//                ]);
//
//            }
//        }
//
//        if($request->data_videos != null){
//            foreach ($request->data_videos as $data) {
//                FormationPhotos::create([
//                    'path'=>$data['path'],
//                    'titre'=>$data['titre'],
//                    'desc'=>$data['desc'],
//                    'formation_id'=>Formation::max('id'),
//                ]);
//
//            }
//        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_formation(Request $request){
        $id = $request->champ_id;
        LangueFormation::where('formation_id',$id)
            ->delete();
        FormationVideos::where('formation_id',$id)
            ->delete();
        FormationPhotos::where('formation_id',$id)
            ->delete();
        FormationDocument::where('formation_id',$id)
            ->delete();
        Formation::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_formation($id,Request $request){
        $detail = Formation::where('id',$id)->get();
        $list_cat = CategFormation::all();
        $cat = CategFormation::where('id',$detail[0]->categ_formation_id)->value('lib');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_cat" => $list_cat,
            "cat" => $cat,
            "message" => '',
        ]);
    }
    public function update_formation($id,Request $request){
        Formation::where('id',$id)
            ->update([
                'date'=>$request->date,
                'heure'=>$request->heure,
                'sujet'=>$request->sujet,
                'frais'=>$request->frais,
                'nbr_place_max'=>$request->nbr_place_max,
                'enligne'=>$request->enligne,
                'categ_formation_id'=>$request->categ_formation_id,
                'photo_principale'=>$request->photo_principale,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function realiser_formation($id,Request $request){
        Formation::where('id',$id)
            ->update([
                'realiser'=>$request->realiser,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function detail_formation($id){
        $detail = Formation::where('id',$id)
            ->get();
        $list_cat = CategFormation::all();
        $detail_cat = CategFormation::where('id',$detail[0]->categ_formation_id)->get();
        $detail_langue = LangueFormation::where('formation_id',$id)->get();
        $instructeur = User::where('instructeur_id',$detail[0]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$detail[0]->instructeur_id)->value('prenom');
        $detail_instructeur = Instructeur::where('id',$detail[0]->instructeur_id)->get();
        $categ_instructeur = CategInstructeur::where('id',$detail_instructeur[0]->categ_instructeur_id)->get();
        $list_candidats_formations = FormationCandidat::where('formation_id',$id)->get();
        $candidatlist =[];
        $liste_can = Candidat::orderBy('id','desc')->get();
        if (count($list_candidats_formations)>0) {
            for ($i=0; $i <count($list_candidats_formations) ; $i++) {
                $liste = User::where('id',$list_candidats_formations[$i]->user_id)->get();

                if (count($liste)>0) {
                    for ($j=0; $j <count($liste) ; $j++) {
                        array_push($candidatlist,
                            [
                                'id_form'=>$id,
                                'date_validation'=>$list_candidats_formations[$i]->date_validation,
                                'id'=>$liste[$j]->id,
                                'id_form_candidat'=>$list_candidats_formations[$i]->id,
                                'candidat_id'=>$liste[$j]->candidat_id,
                                'nom'=>$liste[$j]->nom,
                                'prenom'=>$liste[$j]->prenom,
                                'tel1'=>Candidat::where('id',$liste[$j]->candidat_id)->value('tel1'),
                                'tel2'=>Candidat::where('id',$liste[$j]->candidat_id)->value('tel2'),
                                'email'=>$liste[$j]->mail,
                                'adr'=>$liste[$j]->adresse,
                                'cp'=>Candidat::where('id',$liste[$j]->candidat_id)->value('cp'),
                                'mt_affiliation'=>Candidat::where('id',$liste[$j]->candidat_id)->value('mt_affiliation'),
                                'categ_candidat_desc'=>$liste[$j]->role,
                                'salle_sport_nom'=>SalleDeSport::where('id',Candidat::where('id',$liste[$j]->candidat_id)->value('salle_sport_id'))->value('nom'),
                                'instructeur_id'=>Candidat::where('id',$liste[$j]->candidat_id)->value('instructeur_id'),
                                'instructeur'=>User::where('instructeur_id',Candidat::where('id',$liste[$j]->candidat_id)->value('instructeur_id'))->value('nom').' '.User::where('instructeur_id',Candidat::where('id',$liste[$j]->candidat_id)->value('instructeur_id'))->value('prenom'),
                            ]);
                    }
                }
            }
        }


        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_cat" => $list_cat,
            "detail_cat" => $detail_cat,
            "detail_langue" => $detail_langue,
            "instructeur" => $instructeur,
            "detail_instructeur" => $detail_instructeur,
            "categ_instructeur" => $categ_instructeur,
            "candidatlist" => $candidatlist,
            "liste_can" => $liste_can,
            "message" => '',
        ]);
    }
    public function detail_formation_photos($id){
        $detail = Formation::where('id',$id)
            ->with('photos')
            ->get();

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function detail_formation_videos($id){
        $detail = Formation::where('id',$id)
            ->with('videos')
            ->get();

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    function generateRandomPassword($length = 12) {
        // Caractères possibles dans le mot de passe
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';

        // Convertir les octets aléatoires en une chaîne de caractères
        $bytes = random_bytes($length);
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[ord($bytes[$i]) % strlen($chars)];
        }

        return $password;
    }
    public function inscription_candidat(Request $request, $id_form) {
        $status = false;
        $message = 'Cet e-mail a déjà été utilisé !';

        $verif_email = User::where('email', $request->email)->get();
        if($verif_email->count() == 0) {
            $verif_email_2 = User::where('email', $request->email)->where('role', 'CANDIDAT')->get();
            if($verif_email_2->count() == 0) {
                $message = 'Done !';
                $status = true;

                // Create Candidat first
                $candidat = Candidat::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'tel1' => $request->tel1,
                    'tel2' => $request->tel2,
                    'adr' => $request->adr,
                ]);

                // Create User with the provided password
                $user = User::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'mail' => $request->email,
                    'adresse' => $request->adr,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    'role' => 'CANDIDAT',
                    'candidat_id' => $candidat->id, // Use the created candidat ID
                    'tel' => $request->tel1 ?? $request->tel2,
                ]);

                $data_mail = [
                    'lien' => 'https://zengymhealth.com/login/',
                    'email' => $request->email,
                    'password' => $request->password, // Send the actual password used
                ];

                // Envoyer l'e-mail
                // Mail::to($request->email)->send(new ZenGymConfirmationEmail($data_mail));

                // Attempt login with the provided password
                $token = Auth::guard('api')->attempt([
                    'email' => $request->email,
                    'password' => $request->password // Use the request password, not $pass
                ]);

                if (!$token) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized',
                    ], 401);
                }

                $user = Auth::guard('api')->user();
            } else {
                $status = false;
                $message = 'Vous possédez déjà un compte en tant que candidat !';
            }
        }

        return response()->json([
            "status" => $status,
            "message" => $message,
            'user' => $user ?? [],
            'authorisation' => [
                'token' => $token ?? '',
                'type' => 'bearer',
            ]
        ]);
    }
    public function payer_candidat(Request $request,$id_form){
        FormationCandidat::create([
            'methode_paiement'=>$request->methode_paiement,
            "paiement_status"=>$request->paiement_status,
            "ref"=>$request->ref,
            'formation_id'=>$id_form,
            'user_id'=>$request->user_id,
            'date_validation'=>$request->date_validation,
        ]);
        Formation::where('id',$id_form)->update([
            'nbr_participant'=> intval(Formation::where('id',$id_form)->value('nbr_participant'))+1,
            'nbr_place_max'=> intval(Formation::where('id',$id_form)->value('nbr_place_max'))-1,
        ]);
        if($request->methode_paiement == 'Konnect+'){
            if($request->paiement_status){
                $this->transferer_candidat_vers_instructeur($request->user_id,$id_form);
            }
        }


        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function transferer_candidat_vers_instructeur($id_user,$id_formation){
        $FormationCandidat_detail = FormationCandidat::where('id',$id_formation)
            ->where('user_id',$id_user)
            ->get();
        $detail_user = User::where('id',$id_user)
            ->get();
        $instructeur = Instructeur::create([
            'categ_instructeur_id'=>3
        ]);
        Candidat::where('id',$detail_user[0]->candidat_id)->delete();
        $user = User::where('id',$id_user)
            ->update([
                'role'=>'INSTRUCTEUR',
                'instructeur_id'=>$instructeur->id,
                'candidat_id'=>null,
            ]);

        $newDate = date('Y-m-d', strtotime('+1 month'));
        $active=false;
        if($FormationCandidat_detail[0]->paiement_status){
            $active=true;
            AbonnementUser::where('user_id',$id_user)
                ->update([
                    'active'=>false,
                ]);
            VenteAbo::where('instructeur_id',$instructeur->id)
                ->update([
                    'dernier'=>false,
                ]);
        }

        AbonnementUser::create([
            'titre'=>'Abonnement mensuel',
            'date_paie'=>$FormationCandidat_detail[0]->date_validation,
            'ref'=>$FormationCandidat_detail[0]->ref,
            'status_paie'=>$FormationCandidat_detail[0]->paiement_status,
            'type_paie'=>$FormationCandidat_detail[0]->methode_paiement,
            'type_abo_id'=>'4',
            'date_deb'=>date('Y-m-d'),
            'date_fin'=>$newDate,
            'active'=>$active,
            'user_id'=>$id_user,

        ]);

        $max_id = VenteAbo::max('id');
        $code = 'VA_00'.$max_id;
        $detail_formation = Formation::where('id',$FormationCandidat_detail[0]->formation_id)->get();
        $vente_abo = VenteAbo::create([
            'code'=>$code,
            'date'=>$FormationCandidat_detail[0]->date_validation,
            'montant_ttc'=>$detail_formation[0]->frais,
            'paiement'=>$detail_formation[0]->frais,
            'solder'=>$FormationCandidat_detail[0]->paiement_status,
            'date_deb'=>date('Y-m-d'),
            'date_fin'=>$newDate,
            'type_abo_id'=>4,
            'instructeur_id'=>$instructeur->id,
            'dernier'=>$active,
            'created_at' => now(),
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function aprouver_dmd_formation($id){
        Formation::where('id',$id)
            ->update([
                'approuver'=>true,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function refuser_dmd_formation($id){
        Formation::where('id',$id)
            ->update([
                'approuver'=>false,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function affecter_candidat(Request $request){
        $verif= FormationCandidat::where('formation_id',$request->formation_id)->where('user_id',User::where('candidat_id',$request->candidat_id)->value('id'))->count();
        if($verif==0){
            FormationCandidat::create([
                'date_validation'=>$request->date_validation,
                'formation_id'=>$request->formation_id,
                'paiement_status'=>true,
                'user_id'=>User::where('candidat_id',$request->candidat_id)->value('id') ,

            ]);
            
        }
        if($verif>0){
            FormationCandidat::where('formation_id',$request->formation_id)->where('user_id',User::where('candidat_id',$request->candidat_id)->value('id'))->update([
                'date_validation'=>$request->date_validation,
                'paiement_status'=>true,
                'formation_id'=>$request->formation_id,
            ]);
            
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);


    }
    public function delete_affect_candidat(Request $request){
        $id = $request->champ_id;
        $form_id = $request->form_id;
        FormationCandidat::where('id',$id)
            ->delete();

        return response()->json([
            "status" => true,
            "message" => $form_id,
        ]);
    }

    public function show($id)
    {
        $formation = Formation::with([
            'candidats.user',
            'utilisateurs.user'
        ])->findOrFail($id);
        $user = Auth::user();

        // Check access rights
        $isInstructeur = $formation->instructeur_id && $user->instructeur_id && $formation->instructeur_id === $user->instructeur_id;
        $isOrganisateur = $formation->organisateur_id === $user->id;

        $isCandidat = FormationCandidat::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->exists();

        $isFormationUser = FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->exists();

        if (! $isInstructeur && ! $isOrganisateur && ! $isCandidat && ! $isFormationUser) {
            return response()->json([
                "status" => false,
                "message" => "Accès interdit à cette formation."
            ], 403);
        }

        return response()->json([
            "status" => true,
            "message" => '',
            "formation" => $formation,
        ]);
    }
    public function add_photos_formation($id,Request $request){
        if($request->photos){
            foreach ($request->photos as $file) {
                FormationPhotos::create([
                    'photo'=>$file,
                    'formation_id'=>$id,
                ]);
            }
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_photo_formation($id){
        FormationPhotos::where('id',$id)->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function encaisse_formation($id,Request $request){
        $formation_detail = Formation::where('id',$id)->get();
        $instructeur_detail = Instructeur::where('id',$formation_detail[0]->instructeur_id)->get() ;
        $compte_detail = Compte::where('instructeur_id',$formation_detail[0]->instructeur_id)->get() ;
        $pourcentage_detail = Pourcentage::where('cat_inst_id',$instructeur_detail[0]->categ_instructeur_id)->get() ;
        $tot_ttc = $formation_detail[0]->frais;
        $amount_to_remove = ($pourcentage_detail[0]->pr_formation / 100) * $tot_ttc;

        $max_id = Operation::max('id');
        $code = 'OP_FR_00'.$max_id+1;
        Operation::create([
            'code'=>$code,
            'date'=>now(),
            'montant'=>$amount_to_remove,
            'compte_id'=>$compte_detail[0]->id,
            'type'=>'Crédit',
        ]);
        Compte::where('id',$compte_detail[0]->id)
            ->update([
                'soldecpte'=>floatval($compte_detail[0]->soldecpte)+floatval($amount_to_remove),
                'datedernmodif'=>now(),
            ]);
        Formation::where('id',$id)
            ->update([
                'encaisse'=>true,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
}
