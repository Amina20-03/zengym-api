<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CategFormation;
use App\Models\CategInstructeur;
use App\Models\Evenement;
use App\Models\EvenementsPhotos;
use App\Models\EvenementsVideos;
use App\Models\EvennementInstructeur;
use App\Models\Formation;
use App\Models\Instructeur;
use App\Models\LangueEvennement;
use App\Models\Pays;
use App\Models\Candidat;
use App\Models\SalleDeSport;
use App\Models\TypeEvenement;
use App\Models\EvenementCandidat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ZenGymConfirmationEmail;

class EvenementController extends Controller
{
    public function index_type(){
        $liste = TypeEvenement::all();
        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_type(Request $request){
        $max_id = TypeEvenement::max('id');
        $code = 'TE_00'.$max_id;
        $evenement_national = false;
        if ($request->evenement_national == 'on'){
            $evenement_national = true;
        }
        TypeEvenement::create([
            'code'=>$code,
            'desc'=>$request->desc,
            'evenement_national'=>$evenement_national,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_type($id,Request $request){
        $detail = TypeEvenement::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_type($id,Request $request){
        $evenement_national = false;
        if ($request->evenement_national == 'on'){
            $evenement_national = true;
        }
        TypeEvenement::where('id',$id)
            ->update([
                'desc'=>$request->desc,
                'evenement_national'=>$evenement_national,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_type(Request $request){
        $id = $request->champ_id;
        Evenement::where('type_even_id',$id)
            ->delete();
        TypeEvenement::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_evenement(){
        $evenementlist =[];
        $liste = Evenement::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($evenementlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'fait'=>$liste[$j]->fait,
                        'date_deb'=>$liste[$j]->date_deb,
                        'date_fin'=>$liste[$j]->date_fin,
                        'heure_deb'=>$liste[$j]->heure_deb,
                        'heure_fin'=>$liste[$j]->heure_fin,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'nbr_place_dispo'=>$liste[$j]->nbr_place_dispo,
                        'nbr_place_restant'=>$liste[$j]->nbr_place_restant,
                        'type_even_desc'=>TypeEvenement::where('id',$liste[$j]->type_even_id)->value('desc'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'approuver'=>$liste[$j]->approuver,
                        'refuser'=>$liste[$j]->refuser,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $evenementlist,
            "message" => '',
        ]);
    }
    public function create_evenement($type_even_id = null){
        // Si type_even_id non fourni, prendre le premier type disponible
        if (!$type_even_id) {
            $type_even_id = TypeEvenement::orderBy('id')->value('id');
        }
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
        $list_type_even = TypeEvenement::all();
        $list_pays = Pays::all();

        $detail_type_event = TypeEvenement::where('id',$type_even_id)->get();
        return response()->json([
            "status" => true,
            'list_instructeurs' => $instructeurlist,
            'list_type_even' => $list_type_even,
            'list_pays' => $list_pays,
            'detail_type_event' => $detail_type_event,
            "message" => '',
        ]);
    }
    public function fetch_events()
    {
        $evenlist =[];
        $list_even = Evenement::all();
        if (count($list_even)>0) {
            for ($j=0; $j <count($list_even) ; $j++) {
                array_push($evenlist,
                    [
                        'id'=>$list_even[$j]->id,
                        'code'=>$list_even[$j]->code,
                        'desc'=>$list_even[$j]->desc,
                        'titre'=>$list_even[$j]->titre,
                        'sujet'=>$list_even[$j]->sujet,
                        'fait'=>$list_even[$j]->fait,
                        'frais'=>$list_even[$j]->frais,
                        'devise'=>$list_even[$j]->devise,
                        'salle'=>$list_even[$j]->salle,
                        'date_deb'=>$list_even[$j]->date_deb,
                        'date_fin'=>$list_even[$j]->date_fin,
                        'heure_deb'=>$list_even[$j]->heure_deb,
                        'heure_fin'=>$list_even[$j]->heure_fin,
                        'nbr_participant'=>$list_even[$j]->nbr_participant,
                        'nbr_place_dispo'=>$list_even[$j]->nbr_place_dispo,
                        'nbr_place_restant'=>$list_even[$j]->nbr_place_restant,
                        'approuver'=>$list_even[$j]->approuver,
                        'refuser'=>$list_even[$j]->refuser,
                        'instructeur_id'=>$list_even[$j]->instructeur_id,
                        'type_desc'=>TypeEvenement::where('id',$list_even[$j]->type_even_id)->value('desc'),
                        'nom_instructeur'=>User::where('instructeur_id',$list_even[$j]->id)->value('nom'),
                        'prenom_instructeur'=>User::where('instructeur_id',$list_even[$j]->id)->value('prenom'),
                        'title' => $list_even[$j]->code . ' - ' . $list_even[$j]->desc,
                        'start' => $list_even[$j]->date_deb . 'T' . $list_even[$j]->heure_deb,
                        'end' => $list_even[$j]->date_fin . 'T' . $list_even[$j]->heure_fin,
                        'allDay' => false,
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'evenlist' => $evenlist,
            "message" => '',
        ]);
    }
    public function fetchCeremoniesByMonth(Request $request)
    {
        // Validate the request
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Fetch ceremonies for the selected month
        $ceremonies = Evenement::where('date_fin', '>=', $request->start_date)
            ->where('date_deb', '<=', $request->end_date)
            ->get();

        // Return the ceremonies as JSON
        return response()->json($ceremonies);
    }
    public function delete_evenement(Request $request){
        $id = $request->champ_id;
        EvennementInstructeur::where('evennement_id',$id)
            ->delete();
        EvenementsPhotos::where('event_id',$id)
            ->delete();
        EvenementsVideos::where('event_id',$id)
            ->delete();
        EvenementCandidat::where('event_id',$id)
            ->delete();
        LangueEvennement::where('evennement_id',$id)
            ->delete();
        Evenement::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function add_evenement(Request $request){
        $max_id = Evenement::max('id');
        $code = 'E_00'.$max_id+1;
        $list_instr_id_list = explode("|", $request->instr_id_list);
        Evenement::create([
            'code'=>$code,
            'type_even_id'=>$request->type_even_id,
            'titre'=>$request->titre,
            'sujet'=>$request->sujet,
            'desc'=>$request->desc,
            'langue'=>$request->langue,
            'salle'=>$request->salle,
            'info_sur_lieu'=>$request->info_sur_lieu,
            'date_deb'=>$request->date_deb,
            'date_fin'=>$request->date_fin,
            'heure_deb'=>$request->heure_deb,
            'heure_fin'=>$request->heure_fin,
            'frais'=>$request->frais,
            'devise'=>$request->devise,
            'nbr_place_dispo'=>$request->nbr_place_dispo,
            'contact'=>$request->contact,
            'participant_a_levennement'=>$request->participant_a_levennement,
            'participant_non_inscrit'=>$request->participant_non_inscrit,
            'nbr_participant'=>$request->nbr_participant,
            'nbr_place_restant'=>$request->nbr_place_restant,
            'instructeur_id'=>$request->instructeur_id,
            'pays_id'=>$request->pays_id,
            'approuver'=>$request->approuver,
            'fait'=>false,
        ]);

        if ($list_instr_id_list != null) {
            if (count($list_instr_id_list) > 1) {
                for ($i=0;$i<count($list_instr_id_list);$i++) {
                    if ($list_instr_id_list[$i] != "") {
                        EvennementInstructeur::create([
                            'date_validation'=>date('Y-m-d'),
                            'evennement_id'=>Evenement::max('id'),
                            'user_id'=>User::where('instructeur_id',$list_instr_id_list[$i])->value('id') ,
                        ]);

                    }
                }
            }

        }
        $liste_lang = $request->langue;
        if(count($liste_lang)>0){
            for ($i=0;$i<count($liste_lang);$i++){
                LangueEvennement::create([
                    'langue'=>$liste_lang[$i],
                    'evennement_id'=>Evenement::max('id'),
                ]);
            }
        }

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    /**
     * Demandes de l'instructeur connecté (triées par date desc)
     */
    public function my_dmd_evennements(Request $request){
        $instructeur_id = $request->instructeur_id;
        $liste = Evenement::where('instructeur_id', $instructeur_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($liste as $ev) {
            $affiche_url = null;
            if ($ev->affiche) {
                $affiche_url = 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $ev->affiche;
            }
            $result[] = [
                'id'          => $ev->id,
                'code'        => $ev->code,
                'titre'       => $ev->titre,
                'desc'        => $ev->desc,
                'sujet'       => $ev->sujet,
                'date_deb'    => $ev->date_deb,
                'date_fin'    => $ev->date_fin,
                'heure_deb'   => $ev->heure_deb,
                'heure_fin'   => $ev->heure_fin,
                'frais'       => $ev->frais,
                'devise'      => $ev->devise,
                'salle'       => $ev->salle,
                'nbr_place_dispo' => $ev->nbr_place_dispo,
                'approuver'   => $ev->approuver,
                'refuser'     => $ev->refuser,
                'affiche'     => $ev->affiche,
                'affiche_url' => $affiche_url,
                'type_even_desc' => TypeEvenement::where('id', $ev->type_even_id)->value('desc'),
                'created_at'  => $ev->created_at,
            ];
        }

        return response()->json([
            'status'  => true,
            'liste'   => $result,
            'message' => '',
        ]);
    }

    public function index_dmd_evennement(){
        $evennementlist =[];
        // Retourner toutes les demandes non encore traitées (approuver=null ou false) + refusées
        // L'instructeur voit : en attente (approuver=null) + refusées (refuser=true)
        // L'admin voit : toutes sauf acceptées
        $liste = Evenement::whereNull('approuver')
            ->orWhere(function($q){ $q->where('approuver', false)->where('refuser', true); })
            ->orderBy('date_deb','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                $affiche_url = null;
                if($liste[$j]->affiche){
                    $affiche_url = 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/'.$liste[$j]->affiche;
                }
                array_push($evennementlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'fait'=>$liste[$j]->fait,
                        'frais'=>$liste[$j]->frais,
                        'devise'=>$liste[$j]->devise,
                        'date_deb'=>$liste[$j]->date_deb,
                        'date_fin'=>$liste[$j]->date_fin,
                        'heure_deb'=>$liste[$j]->heure_deb,
                        'heure_fin'=>$liste[$j]->heure_fin,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'nbr_place_dispo'=>$liste[$j]->nbr_place_dispo,
                        'nbr_place_restant'=>$liste[$j]->nbr_place_restant,
                        'titre'=>$liste[$j]->titre,
                        'sujet'=>$liste[$j]->sujet,
                        'participant_a_levennement'=>$liste[$j]->participant_a_levennement,
                        'participant_non_inscrit'=>$liste[$j]->participant_non_inscrit,
                        'salle'=>$liste[$j]->salle,
                        'contacte'=>$liste[$j]->contacte,
                        'contact'=>$liste[$j]->contacte,
                        'info_sur_lieu'=>$liste[$j]->info_sur_lieu,
                        'approuver'=>$liste[$j]->approuver,
                        'refuser'=>$liste[$j]->refuser,
                        'affiche'=>$liste[$j]->affiche,
                        'affiche_url'=>$affiche_url,
                        'type_even_id'=>$liste[$j]->type_even_id,
                        'type_even_desc'=>TypeEvenement::where('id',$liste[$j]->type_even_id)->value('desc'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                        'pays_id'=>$liste[$j]->pays_id,
                        'pays_desc'=>$liste[$j]->pays_id ? \App\Models\Pays::where('id',$liste[$j]->pays_id)->value('desc') : null,
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $evennementlist,
            "message" => '',
        ]);

    }
    public function detail_evenement($id)
    {
        $detail = Evenement::find($id);


        $list_cat = TypeEvenement::all();

        $detail_cat = TypeEvenement::find($detail->type_even_id);

        $detail_langue = LangueEvennement::where('evennement_id', $id)->get();

        if ($detail->instructeur_id) {
            $user = User::where('instructeur_id', $detail->instructeur_id)->first();
            $instructeur = $user ? $user->nom . ' ' . $user->prenom : '';

            $detail_instructeur = Instructeur::find($detail->instructeur_id);
            $categ_instructeur = $detail_instructeur
                ? CategInstructeur::find($detail_instructeur->categ_instructeur_id)
                : null;
        } else {
            $user = auth()->user();
            $instructeur = $user ? $user->nom . ' ' . $user->prenom : '';
            $detail_instructeur = null;
            $categ_instructeur = null;
        }

        $list_instructeur_evennements = EvennementInstructeur::where('evennement_id', $id)->get();

        $instructeurlist = [];

        foreach ($list_instructeur_evennements as $item) {
            $user = User::find($item->user_id);
            if (!$user || !$user->instructeur_id) continue;

            $inst = Instructeur::find($user->instructeur_id);
            if (!$inst) continue;

            $instructeurlist[] = [
                'id' => $inst->id,
                'profession' => $inst->profession,
                'commentaire' => $inst->commentaire,
                'sexe' => $inst->sexe,
                'date_naiss' => $inst->date_naiss,
                'photo' => $inst->photo,
                'cin' => $inst->cin,
                'pays_desc' => $inst->pays_id ? Pays::find($inst->pays_id)?->desc : null,
                'categ_instructeur_desc' => $inst->categ_instructeur_id ? CategInstructeur::find($inst->categ_instructeur_id)?->desc : null,
                'nom' => User::where('instructeur_id', $inst->id)->value('nom'),
                'prenom' => User::where('instructeur_id', $inst->id)->value('prenom'),
                'mail' => User::where('instructeur_id', $inst->id)->value('mail'),
                'adresse' => User::where('instructeur_id', $inst->id)->value('adresse'),
                'tel' => User::where('instructeur_id', $inst->id)->value('tel'),
                'email' => User::where('instructeur_id', $inst->id)->value('email'),
            ];
        }

        $liste_inst = User::whereNotNull('instructeur_id')->orderByDesc('id')->get();

        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_cat" => $list_cat,
            "detail_cat" => $detail_cat,
            "detail_langue" => $detail_langue,
            "instructeur" => $instructeur,
            "detail_instructeur" => $detail_instructeur,
            "categ_instructeur" => $categ_instructeur,
            "instructeurlist" => $instructeurlist,
            "liste_inst" => $liste_inst,
            "message" => '',
        ]);
    }
    public function detail_evenement_photos($id)
    {
        $detail        = Evenement::find($id);
        $detail_photos = EvenementsPhotos::where('event_id', $id)->get()
            ->map(function ($p) {
                $url = null;
                if ($p->photo) {
                    // Nouveau système : chemin storage
                    if (!str_starts_with($p->photo, '/9j/') && strlen($p->photo) < 500) {
                        $url = 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $p->photo;
                    } else {
                        // Ancien base64
                        $url = 'data:image/jpg;base64,' . $p->photo;
                    }
                }
                return [
                    'id'        => $p->id,
                    'lib'       => $p->lib,
                    'photo'     => $p->photo,
                    'photo_url' => $url,
                    'event_id'  => $p->event_id,
                ];
            });
        $detail_videos = EvenementsVideos::where('event_id', $id)->get();

        return response()->json([
            "status"        => true,
            "detail"        => $detail,
            "detail_photos" => $detail_photos,
            "detail_videos" => $detail_videos,
            "message"       => '',
        ]);
    }
    public function add_evenement_photo(Request $request, $id){
        // Nouveau système : fichiers uploadés → WebP storage
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $this->storeAffiche($file);
                if ($path) {
                    EvenementsPhotos::create([
                        'lib'      => '',
                        'event_id' => $id,
                        'photo'    => $path,
                    ]);
                }
            }
        }
        // Ancien système base64 (compatibilité) — gardé temporairement
        elseif ($request->images) {
            $photos = $request->images;
            foreach ($photos as $photo) {
                EvenementsPhotos::create([
                    'lib'      => '',
                    'event_id' => $id,
                    'photo'    => $photo,
                ]);
            }
        }

        return response()->json([
            "status"  => true,
            "message" => '',
        ]);
    }
    public function add_evenement_video(Request $request, $id){
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if (!$disk->exists('evenements/videos')) {
            $disk->makeDirectory('evenements/videos');
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $uuid = \Illuminate\Support\Str::uuid();
                $ext  = $file->getClientOriginalExtension() ?: 'mp4';
                $path = 'evenements/videos/' . $uuid . '.' . $ext;
                $disk->put($path, file_get_contents($file->getRealPath()));
                EvenementsVideos::create([
                    'lib'      => $file->getClientOriginalName(),
                    'event_id' => $id,
                    'path'     => $path,
                ]);
            }
        } elseif ($request->video_path) {
            // Ancien système
            foreach ($request->video_path as $video) {
                EvenementsVideos::create([
                    'lib'      => '',
                    'event_id' => $id,
                    'path'     => $video,
                ]);
            }
        }

        return response()->json([
            "status"  => true,
            "message" => '',
        ]);
    }
    public function delete_evenement_photo($id){
        EvenementsPhotos::where('id',$id)->delete();

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_evenement_video($id){
        EvenementsVideos::where('id',$id)->delete();

        return response()->json([
            "status" => true,
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
    public function inscription_candidat(Request $request){
        $status = false;
        $message = 'Cet e-mail a déjà été utilisé !';
        $pass = $this->generateRandomPassword(12);
        $verif_email = User::where('email',$request->email)->get();
        if($verif_email->count() == 0){
            $verif_email_2 = User::where('email',$request->email)->where('role','CANDIDAT')->get();
            if($verif_email_2->count() == 0){
                $message = 'Done !';
                $status = true;
                Candidat::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'email'=>$request->email,
                    'tel1'=>$request->tel1,
                    'tel2'=>$request->tel2,
                    'adr'=>$request->adr,
                ]);

                User::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'mail'=>$request->email,
                    'adresse'=>$request->adr,
                    'email'=>$request->email,
                    'password'=>\Illuminate\Support\Facades\Hash::make($request->password),
                    'role'=>'CANDIDAT',
                    'candidat_id'=>Candidat::max('id'),
                    'tel'=>$request->tel1??$request->tel2,
                ]);
//                $data_mail = [
//                    'lien' => 'https://zengymhealth.com/login/',
//                    'email' => $request->email,
//                    'password' => $pass,
//                ];
//
//                // Envoyer l'e-mail
//                Mail::to($request->email)->send(new ZenGymConfirmationEmail($data_mail));


                $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

                if (!$token) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized',
                    ], 401);
                }

                $user = Auth::guard('api')->user();

            }
            else{
                $status = false;
                $message = 'Vous possédez déjà un compte en tant que candidat !';
            }


        }


        return response()->json([
            "status" => $status,
            "message" => $message,
            'user' => $user ?? [],
            'authorisation' => [
                'token' => $token ??'',
                'type' => 'bearer',
            ]
        ]);

    }
    public function payer_candidat(Request $request,$event_id){
        $detail_EvenementCandidat = EvenementCandidat::where('event_id',$event_id)
            ->where('user_id',$request->user_id)
            ->get();
        if((empty($detail_EvenementCandidat)) && (count($detail_EvenementCandidat)==0)){
            EvenementCandidat::create([
                'methode_paiement'=>$request->methode_paiement,
                "paiement_status"=>$request->paiement_status,
                "ref"=>$request->ref,
                'event_id'=>$event_id,
                'user_id'=>$request->user_id,
                'date_validation'=>$request->date_validation,
            ]);
            Evenement::where('id',$event_id)->where('nbr_place_dispo','!=','0')->update([
                'nbr_participant'=> intval(Evenement::where('id',$event_id)->value('nbr_participant'))+1,
                'nbr_place_dispo'=> intval(Evenement::where('id',$event_id)->value('nbr_place_dispo'))-1,
            ]);
        }



        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function create_dmd_evennement(Request $request){
        $list_cat = TypeEvenement::all();
        return response()->json([
            "status" => true,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add_dmd_evennement(Request $request){

        $pays_id        = empty($request->pays_id)        ? null : $request->pays_id;
        $instructeur_id = empty($request->instructeur_id) ? null : $request->instructeur_id;

        // ----------------------------------------------------------------
        // Vérification conflit horaire : même jour + chevauchement d'heure
        // ----------------------------------------------------------------
        $conflit = Evenement::where('date_deb', $request->date_deb)
            ->where('instructeur_id', $instructeur_id)
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    // Nouvel événement commence pendant un existant
                    $q2->where('heure_deb', '<=', $request->heure_deb)
                       ->where('heure_fin', '>', $request->heure_deb);
                })->orWhere(function ($q2) use ($request) {
                    // Nouvel événement termine pendant un existant
                    $q2->where('heure_deb', '<', $request->heure_fin)
                       ->where('heure_fin', '>=', $request->heure_fin);
                })->orWhere(function ($q2) use ($request) {
                    // Nouvel événement englobe un existant
                    $q2->where('heure_deb', '>=', $request->heure_deb)
                       ->where('heure_fin', '<=', $request->heure_fin);
                });
            })
            ->whereNull('refuser') // ignorer les refusés
            ->exists();

        if ($conflit) {
            return response()->json([
                'status'  => false,
                'message' => 'Un événement existe déjà pour cet instructeur à cette date et cette plage horaire.',
            ], 409);
        }

        // ----------------------------------------------------------------
        // Upload affiche
        // ----------------------------------------------------------------
        $affichePath = null;
        if ($request->hasFile('affiche')) {
            $affichePath = $this->storeAffiche($request->file('affiche'));
        }

        // ----------------------------------------------------------------
        // Création
        // ----------------------------------------------------------------
        $max_id = Evenement::max('id') + 1;
        $even   = Evenement::create([
            'code'                      => 'Ev_00' . $max_id,
            'type_even_id'              => $request->type_even_id,
            'titre'                     => $request->titre,
            'sujet'                     => $request->sujet,
            'desc'                      => $request->desc,
            'salle'                     => $request->salle,
            'info_sur_lieu'             => $request->info_sur_lieu,
            'date_deb'                  => $request->date_deb,
            'date_fin'                  => $request->date_fin ?? $request->date_deb,
            'heure_deb'                 => $request->heure_deb,
            'heure_fin'                 => $request->heure_fin,
            'frais'                     => $request->frais ?? 0,
            'devise'                    => $request->devise ?? 'DT',
            'nbr_place_dispo'           => $request->nbr_place_dispo ?? 0,
            'contacte'                  => $request->contact,
            'participant_a_levennement' => $request->participant_a_levennement,
            'participant_non_inscrit'   => $request->participant_non_inscrit,
            'nbr_participant'           => 0,
            'nbr_place_restant'         => 0,
            'instructeur_id'            => $instructeur_id,
            'pays_id'                   => $pays_id,
            'approuver'                 => null,
            'refuser'                   => null,
            'affiche'                   => $affichePath,
            'fait'                      => false,
        ]);

        // Langues
        $liste_lang = $request->langue;
        if (is_array($liste_lang) && count($liste_lang) > 0) {
            foreach ($liste_lang as $lang) {
                LangueEvennement::create([
                    'langue'        => $lang,
                    'evennement_id' => $even->id,
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'id'      => $even->id,
            'message' => '',
        ]);
    }

    public function delete_dmd_evennement(Request $request){
        $id = $request->champ_id;
        LangueEvennement::where('evennement_id',$id)
            ->delete();
        Evenement::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function aprouver_dmd_evennement($id){
        Evenement::where('id',$id)
            ->update([
                'approuver'=>true,
                'refuser'=>null,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    public function refuser_dmd_evennement($id){
        // Marquer comme refusé — ne pas supprimer, l'instructeur peut voir et supprimer lui-même
        Evenement::where('id',$id)->update([
            'approuver'=>false,
            'refuser'=>true,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }

    /**
     * Modifier une demande d'événement (uniquement si en attente : approuver=null)
     */
    public function update_dmd_evennement($id, Request $request){
        $evenement = Evenement::findOrFail($id);

        // Sécurité : on ne peut modifier que si en attente
        if ($evenement->approuver !== null) {
            return response()->json(['status' => false, 'message' => 'Cette demande ne peut plus être modifiée.'], 403);
        }

        $affichePath = $evenement->affiche;
        if ($request->hasFile('affiche')) {
            if ($affichePath) \Illuminate\Support\Facades\Storage::disk('public')->delete($affichePath);
            $affichePath = $this->storeAffiche($request->file('affiche'));
        }

        $pays_id = ($request->pays_id === 'null' || $request->pays_id === null) ? null : $request->pays_id;

        $evenement->update([
            'titre'                     => $request->titre          ?? $evenement->titre,
            'sujet'                     => $request->sujet          ?? $evenement->sujet,
            'desc'                      => $request->desc           ?? $evenement->desc,
            'salle'                     => $request->salle          ?? $evenement->salle,
            'info_sur_lieu'             => $request->info_sur_lieu  ?? $evenement->info_sur_lieu,
            'date_deb'                  => $request->date_deb       ?? $evenement->date_deb,
            'date_fin'                  => $request->date_fin       ?? $evenement->date_fin,
            'heure_deb'                 => $request->heure_deb      ?? $evenement->heure_deb,
            'heure_fin'                 => $request->heure_fin      ?? $evenement->heure_fin,
            'frais'                     => $request->frais          ?? $evenement->frais,
            'devise'                    => $request->devise         ?? $evenement->devise,
            'nbr_place_dispo'           => $request->nbr_place_dispo ?? $evenement->nbr_place_dispo,
            'contact'                   => $request->contact        ?? $evenement->contacte,
            'participant_a_levennement' => $request->participant_a_levennement ?? $evenement->participant_a_levennement,
            'participant_non_inscrit'   => $request->participant_non_inscrit   ?? $evenement->participant_non_inscrit,
            'pays_id'                   => $pays_id,
            'affiche'                   => $affichePath,
        ]);

        return response()->json(['status' => true, 'message' => 'Demande mise à jour.']);
    }

    /**
     * Helper : stocker une affiche en WebP dans storage/app/public/evenements/
     */
    private function storeAffiche($file): ?string
    {
        // S'assurer que le dossier existe
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if (!$disk->exists('evenements')) {
            $disk->makeDirectory('evenements');
        }

        if (!extension_loaded('gd')) {
            // Fallback : stocker en l'état sans conversion
            $uuid = \Illuminate\Support\Str::uuid();
            $ext  = $file->getClientOriginalExtension() ?: 'jpg';
            $path = 'evenements/' . $uuid . '.' . $ext;
            $disk->put($path, file_get_contents($file->getRealPath()));
            return $path;
        }

        $mime = $file->getMimeType();
        $source = match($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png'               => imagecreatefrompng($file->getRealPath()),
            'image/webp'              => imagecreatefromwebp($file->getRealPath()),
            default                   => null,
        };

        if (!$source) {
            // Fallback si type non reconnu
            $uuid = \Illuminate\Support\Str::uuid();
            $path = 'evenements/' . $uuid . '.jpg';
            $disk->put($path, file_get_contents($file->getRealPath()));
            return $path;
        }

        $w = imagesx($source);
        $h = imagesy($source);

        if ($w > 1200) {
            $r   = 1200 / $w;
            $nw  = 1200;
            $nh  = (int)($h * $r);
            $img = imagecreatetruecolor($nw, $nh);
            // Préserver transparence PNG/WebP
            if (in_array($mime, ['image/png', 'image/webp'])) {
                imagealphablending($img, false);
                imagesavealpha($img, true);
                imagefill($img, 0, 0, imagecolorallocatealpha($img, 0, 0, 0, 127));
            }
            imagecopyresampled($img, $source, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($source);
        } else {
            $img = $source;
        }

        $uuid = \Illuminate\Support\Str::uuid();
        $tmp  = sys_get_temp_dir() . '/' . $uuid . '.webp';
        imagewebp($img, $tmp, 85);
        imagedestroy($img);

        $path   = 'evenements/' . $uuid . '.webp';
        $stored = $disk->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $stored ? $path : null;
    }

    public function realiser_evennement($id){
        Evenement::where('id',$id)
            ->update([
                'fait'=>true,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function index_evennement_by_categ($id_categ){
        $currentDate = Carbon::now();
        $evennementlist =[];
        $list_cat = TypeEvenement::orderBy('id','desc')->get();
        if ($id_categ != 'all'){

        }
        else{
            $id_categ = $list_cat[0]->id;
        }
        $liste = Evenement::where('approuver',true)->where('type_even_id',$id_categ)->orderBy('date_deb','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($evennementlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'fait'=>$liste[$j]->fait,
                        'frais'=>$liste[$j]->frais,
                        'devise'=>$liste[$j]->devise,
                        'date_deb'=>$liste[$j]->date_deb,
                        'date_fin'=>$liste[$j]->date_fin,
                        'heure_deb'=>$liste[$j]->heure_deb,
                        'heure_fin'=>$liste[$j]->heure_fin,
                        'nbr_participant'=>$liste[$j]->nbr_participant,
                        'nbr_place_dispo'=>$liste[$j]->nbr_place_dispo,
                        'nbr_place_restant'=>$liste[$j]->nbr_place_restant,
                        'type_even_id'=>$liste[$j]->type_even_id,
                        'type_even_desc'=>TypeEvenement::where('id',$liste[$j]->type_even_id)->value('desc'),
                        'titre'=>$liste[$j]->titre,
                        'sujet'=>$liste[$j]->sujet,
                        'participant_a_levennement'=>$liste[$j]->participant_a_levennement,
                        'participant_non_inscrit'=>$liste[$j]->participant_non_inscrit,
                        'salle'=>$liste[$j]->salle,
                        'contacte'=>$liste[$j]->contacte,
                        'info_sur_lieu'=>$liste[$j]->info_sur_lieu,
                        'approuver'=>$liste[$j]->approuver,
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),

                    ]);
            }
        }

        $nbr_evenements = Formation::count();
        $detail_cat = TypeEvenement::where('id',$id_categ)->get();

        return response()->json([
            "status" => true,
            'liste' => $evennementlist,
            'list_cat' => $list_cat,
            'nbr_evenements' => $nbr_evenements,
            'detail_cat' => $detail_cat,
            'du_date' => date('Y-m-01'),
            'au_date' => $currentDate->endOfMonth()->format('Y-m-d'),
            'heure_deb' => date('H:i'),
            'heure_fin' => date('H:i'),
            "message" => '',
        ]);

    }
    public function affecter_user(Request $request){
        $verif= EvennementInstructeur::where('evennement_id',$request->evennement_id)->where('user_id',$request->user_id)->count();
        if($verif==0){
            EvennementInstructeur::create([
                'date_validation'=>$request->date_validation,
                'evennement_id'=>$request->evennement_id,
                'user_id'=>$request->user_id,

            ]);
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);


    }
    public function delete_affect_user(Request $request){
        $id = $request->champ_id;
        EvennementInstructeur::where('user_id',User::where('instructeur_id',$id)->value('id'))
            ->delete();

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_affect_candidat(Request $request){
        $id = $request->champ_id;
        EvenementCandidat::where('user_id',$id)
            ->delete();

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function get_candidats_evennement($id){

        $list_candidats_evenements = EvenementCandidat::where('event_id',$id)->get();
        $candidatlist =[];
        $liste_can = Candidat::orderBy('id','desc')->get();
        if (count($list_candidats_evenements)>0) {
            for ($i=0; $i <count($list_candidats_evenements) ; $i++) {
                $liste = User::where('id',$list_candidats_evenements[$i]->user_id)->get();

                if (count($liste)>0) {
                    for ($j=0; $j <count($liste) ; $j++) {
                        array_push($candidatlist,
                            [
                                'date_validation'=>$list_candidats_evenements[$i]->date_validation,
                                'paiement_status'=>$list_candidats_evenements[$i]->paiement_status,
                                'methode_paiement'=>$list_candidats_evenements[$i]->methode_paiement,
                                'id'=>$liste[$j]->id,
                                'user_id'=>$list_candidats_evenements[$i]->user_id,
                                'id_event'=>$list_candidats_evenements[$i]->event_id,
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
            "list_candidats" => $candidatlist,
            "message" => '',
        ]);

    }
    public function change_candidat_payment_status(Request $request){
        EvenementCandidat::where('user_id',$request->user_id)
            ->where('event_id',$request->event_id)
            ->update([
                'paiement_status'=>$request->status_payment,
                'methode_paiement'=>$request->methode_paiement,
                'date_validation'=>date('Y-m-d'),
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function my_evenements(){
        $evenementlist =[];
        $list_candidats_evenements = EvenementCandidat::where('user_id',auth()->user()->id)->get();
        if (count($list_candidats_evenements)>0) {
            for ($j=0; $j <count($list_candidats_evenements) ; $j++) {
                array_push($evenementlist,
                    [
                        'id_event'=>$list_candidats_evenements[$j]->event_id,
                        'event'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('titre'),
                        'event_date_deb'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('date_deb'),
                        'event_date_fin'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('date_fin'),
                        'event_type_even'=>TypeEvenement::where('id',Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('type_even_id'))->value('desc'),
                        'event_salle'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('salle'),
                        'event_status'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('fait'),
                        'event_frais'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('frais'),
                        'event_devise'=>Evenement::where('id',$list_candidats_evenements[$j]->event_id)->value('devise'),
                        'user_id'=>$list_candidats_evenements[$j]->user_id,
                        'user_nom'=>User::where('id',$list_candidats_evenements[$j]->user_id)->value('nom'),
                        'user_prenom'=>User::where('id',$list_candidats_evenements[$j]->user_id)->value('prenom'),
                        'methode_paiement'=>$list_candidats_evenements[$j]->methode_paiement,
                        'paiement_status'=>$list_candidats_evenements[$j]->paiement_status,
                        'date_validation'=>$list_candidats_evenements[$j]->date_validation,
                    ]);
            }
        }

        return response()->json([
            "status" => true,
            'liste' => $evenementlist,
            "message" => '',
        ]);
    }

}
