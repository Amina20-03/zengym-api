<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\CategCandidat;
use App\Models\CategFormation;
use App\Models\CategInstructeur;
use App\Models\DocumentInstructeurCategorie;
use App\Models\Formation;
use App\Models\FormationCandidat;
use App\Models\FormationDocument;
use App\Models\FormationPhotos;
use App\Models\FormationVideos;
use App\Models\Instructeur;
use App\Models\Pays;
use App\Models\PhotoInstructeurCategorie;
use App\Models\SalleDeSport;
use App\Models\User;
use App\Models\UserDocuments;
use App\Models\UserPhotos;
use App\Models\UserVideos;
use App\Models\AboAdherent;
use App\Models\VideoInstructeurCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CandidatController extends Controller
{
    public function index_categ_candidat(){
        $liste = CategCandidat::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_candidat(Request $request){
        $max_id = CategCandidat::max('id')+1;
        $code = 'CC_00'.$max_id;
        CategCandidat::create([
            'code'=>$code,
            'titre'=>$request->titre,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_candidat($id,Request $request){
        $detail = CategCandidat::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_candidat($id,Request $request){
        CategCandidat::where('id',$id)
            ->update([
                'titre'=>$request->titre,
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_candidat(Request $request){
        $id = $request->champ_id;
        Candidat::where('categ_candidat_id',$id)
            ->delete();
        CategCandidat::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_salle_sport(){
        $liste = SalleDeSport::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_salle_sport(Request $request){
        $max_id = SalleDeSport::max('id');
        $code = 'SP_00'.$max_id;
        SalleDeSport::create([
            'code'=>$code,
            'nom'=>$request->nom,
            'adresse'=>$request->adresse,
            'tel1'=>$request->tel1,
            'tel2'=>$request->tel2,
            'email'=>$request->email,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_salle_sport($id,Request $request){
        $detail = SalleDeSport::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_salle_sport($id,Request $request){
        SalleDeSport::where('id',$id)
            ->update([
                'nom'=>$request->nom,
                'adresse'=>$request->adresse,
                'tel1'=>$request->tel1,
                'tel2'=>$request->tel2,
                'email'=>$request->email,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_salle_sport(Request $request){
        $id = $request->champ_id;
        Candidat::where('salle_sport_id',$id)
            ->delete();
        SalleDeSport::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_candidat(){
        $candidatlist =[];
        $liste = Candidat::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($candidatlist,
                    [
                        'id'=>$liste[$j]->id,
                        'nom'=>$liste[$j]->nom,
                        'prenom'=>$liste[$j]->prenom,
                        'tel1'=>$liste[$j]->tel1,
                        'tel2'=>$liste[$j]->tel2,
                        'email'=>$liste[$j]->email,
                        'adr'=>$liste[$j]->adr,
                        'cp'=>$liste[$j]->cp,
                        'mt_affiliation'=>$liste[$j]->mt_affiliation,
                        'categ_candidat_desc'=>CategCandidat::where('id',$liste[$j]->categ_candidat_id)->value('titre'),
                        'salle_sport_nom'=>SalleDeSport::where('id',$liste[$j]->salle_sport_id)->value('nom'),
                        'instructeur_id'=>$liste[$j]->instructeur_id,
                        'instructeur'=>User::where('instructeur_id',$liste[$j]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$liste[$j]->instructeur_id)->value('prenom'),
                        'role'=>User::where('candidat_id',$liste[$j]->id)->value('role'),
                    ]);
            }
        }
        $list_instructeurs = User::where('instructeur_id','!=',null)->get();
        $list_cat = CategCandidat::all();
        $list_salle_sport = SalleDeSport::all();
        return response()->json([
            "status" => true,
            'liste' => $candidatlist,
            'list_instructeurs' => $list_instructeurs,
            'list_cat' => $list_cat,
            'list_salle_sport' => $list_salle_sport,
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
    public function add_candidat(Request $request){
        Candidat::create([
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'tel1'=>$request->tel1,
            'tel2'=>$request->tel2,
            'email'=>$request->email,
            'adr'=>$request->adr,
            'cp'=>$request->cp,
            'role'=>'CANDIDAT',
            'mt_affiliation'=>$request->mt_affiliation,
            'categ_candidat_id'=>$request->categ_candidat_id,
            'salle_sport_id'=>$request->salle_sport_id,
            'instructeur_id'=>$request->instructeur_id,
            'created_at' => now(),
        ]);
        User::create([
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'mail'=>$request->email,
            'email'=>$request->email,
            'password'=> \Illuminate\Support\Facades\Hash::make($request->password),
            'candidat_id'=>Candidat::max('id'),
            'role'=>'CANDIDAT',
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_candidat($id,Request $request){
        $detail = Candidat::where('id',$id)->get();
        $list_instructeurs = User::where('instructeur_id','!=',null)->get();
        $instructeur = User::where('instructeur_id',$detail[0]->instructeur_id)->value('nom').' '.User::where('instructeur_id',$detail[0]->instructeur_id)->value('prenom');
        $list_cat = CategCandidat::all();
        $cat = CategCandidat::where('id',$detail[0]->categ_candidat_id)->value('titre');
        $list_salle_sport = SalleDeSport::all();
        $salle_sport = SalleDeSport::where('id',$detail[0]->salle_sport_id)->value('nom');
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "list_instructeurs" => $list_instructeurs,
            "instructeur" => $instructeur,
            "list_cat" => $list_cat,
            "cat" => $cat,
            "list_salle_sport" => $list_salle_sport,
            "salle_sport" => $salle_sport,
            "message" => '',
        ]);
    }
    public function update_candidat($id,Request $request){
        Candidat::where('id',$id)
            ->update([
                'nom'=>$request->nom,
                'prenom'=>$request->prenom,
                'tel1'=>$request->tel1,
                'tel2'=>$request->tel2,
                'email'=>$request->email,
                'adr'=>$request->adr,
                'cp'=>$request->cp,
                'mt_affiliation'=>$request->mt_affiliation,
                'categ_candidat_id'=>$request->categ_candidat_id,
                'salle_sport_id'=>$request->salle_sport_id,
                'instructeur_id'=>$request->instructeur_id,
                'updated_at' => now()
            ]);

        // Mise à jour user — ne changer le password que s'il est fourni ET non vide
        $userUpdate = [
            'email' => $request->email,
            'mail'  => $request->email,
        ];
        if (!empty($request->password) && strlen(trim($request->password)) >= 1) {
            $userUpdate['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        User::where('candidat_id', $id)->update($userUpdate);

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function delete_candidat(Request $request){
        $id = $request->champ_id;

        // Récupérer l'utilisateur lié au candidat
        $user = User::where('candidat_id', $id)->first();

        if ($user) {
            // Supprimer d'abord les enregistrements dans abo_adherents
            AboAdherent::where('user_id', $user->id)->delete();

            // Ensuite supprimer l'utilisateur
            $user->delete();
        }

        // Finalement supprimer le candidat
        Candidat::where('id', $id)->delete();

        return response()->json([
            "status" => true,
            "message" => 'Candidat supprimé avec succès',
        ]);
    }
    public function passage_vers_instructeur_form($id,Request $request){
        $detail_user = User::where('candidat_id',$id)->get();
        $list_pays = Pays::all();
        $list_cat = CategInstructeur::all();

        return response()->json([
            "status" => true,
            "detail_user" => $detail_user,
            'list_pays' => $list_pays,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function passage_vers_instructeur($id_user, Request $request)
    {
        // ✅ Initialisation OBLIGATOIRE
        $liste_formation_video = [];
        $liste_formation_documents = [];
        $formation_candidat_list = [];

        $detail_formation_candidat = FormationCandidat::where('user_id', $id_user)->get();



            // 🔹 Création instructeur
            $instructeur = Instructeur::create([
                'profession' => $request->profession,
                'commentaire' => $request->commentaire,
                'sexe' => $request->sexe,
                'date_naiss' => $request->date_naiss,
                'photo' => $request->photo ?? null,
                'cin' => $request->cin,
                'pays_id' => $request->pays_id,
                'categ_instructeur_id' => $request->categ_instructeur_id,
            ]);

            // 🔹 Mise à jour user
            User::where('id', $id_user)->update([
                'role' => 'INSTRUCTEUR',
                'instructeur_id' => $instructeur->id,
            ]);
        if ($detail_formation_candidat->count() > 0) {
            foreach ($detail_formation_candidat as $item) {

                $formation = Formation::find($item->formation_id);
                if (!$formation) {
                    continue;
                }

                // 🔹 Liste formations candidat
                $formation_candidat_list[] = [
                    'id' => $formation->id,
                    'code' => $formation->code,
                    'date' => $formation->date,
                    'heure' => $formation->heure,
                    'nbr_participant' => $formation->nbr_participant,
                    'titre' => $formation->titre,
                    'sujet' => $formation->sujet,
                    'frais' => $formation->frais,
                    'categ_formation_desc' => CategFormation::where('id', $formation->categ_formation_id)->value('lib'),
                    'instructeur_id' => $formation->instructeur_id,
                    'instructeur' =>
                        User::where('instructeur_id', $formation->instructeur_id)->value('nom') . ' ' .
                        User::where('instructeur_id', $formation->instructeur_id)->value('prenom'),
                ];

                /*
                |--------------------------------------------------
                | PHOTOS
                |--------------------------------------------------
                */
                $photoCategorie = PhotoInstructeurCategorie::create([
                    'code' => 'CPI_' . str_pad(PhotoInstructeurCategorie::max('id') + 1, 3, '0', STR_PAD_LEFT),
                    'desc' => $formation->code,
                ]);

                $photos = FormationPhotos::where('formation_id', $formation->id)->get();
                foreach ($photos as $photo) {
                    UserPhotos::create([
                        'photo' => $photo->photo,
                        'titre' => $photo->titre,
                        'desc' => $photo->desc,
                        'categ_id' => $photoCategorie->id,
                        'user_id' => $id_user,
                    ]);
                }

                /*
                |--------------------------------------------------
                | VIDEOS
                |--------------------------------------------------
                */
                $videoCategorie = VideoInstructeurCategorie::create([
                    'code' => 'CVI_' . str_pad(VideoInstructeurCategorie::max('id') + 1, 3, '0', STR_PAD_LEFT),
                    'desc' => $formation->code,
                ]);

                $videos = FormationVideos::where('formation_id', $formation->id)->get();
                foreach ($videos as $video) {

                    $parts = explode('/', $video->path);
                    $path = $instructeur->id . '/'
                        . User::where('id', $id_user)->value('nom') . '-'
                        . User::where('id', $id_user)->value('prenom')
                        . '/videos/' . end($parts);

                    UserVideos::create([
                        'path' => $path,
                        'titre' => $video->titre,
                        'desc' => $video->desc,
                        'categ_id' => $videoCategorie->id,
                        'user_id' => $id_user,
                    ]);

                    // 👇 sauvegarde pour l'API
                    $liste_formation_video[] = $video;
                }

                /*
                |--------------------------------------------------
                | DOCUMENTS
                |--------------------------------------------------
                */
                $documentCategorie = DocumentInstructeurCategorie::create([
                    'code' => 'CDI_' . str_pad(DocumentInstructeurCategorie::max('id') + 1, 3, '0', STR_PAD_LEFT),
                    'desc' => $formation->code,
                ]);

                $documents = FormationDocument::where('formation_id', $formation->id)->get();
                foreach ($documents as $document) {

                    $parts = explode('/', $document->path);
                    $path = $instructeur->id . '/'
                        . User::where('id', $id_user)->value('nom') . '-'
                        . User::where('id', $id_user)->value('prenom')
                        . '/docs/' . end($parts);

                    UserDocuments::create([
                        'path' => $path,
                        'titre' => $document->titre,
                        'desc' => $document->desc,
                        'categ_id' => $documentCategorie->id,
                        'user_id' => $id_user,
                    ]);

                    // 👇 sauvegarde pour l'API
                    $liste_formation_documents[] = $document;
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Passage vers instructeur effectué',
            'instructeur_id' => $instructeur->id ?? null,
            'nom' => User::where('id', $id_user)->value('nom'),
            'prenom' => User::where('id', $id_user)->value('prenom'),
            'formation_candidat_list' => $formation_candidat_list,
            'liste_formation_video' => $liste_formation_video,
            'liste_formation_documents' => $liste_formation_documents,
        ]);
    }


    public function upload_photo_candidat(Request $request, $id)
    {
        if (!$request->hasFile('photo')) {
            return response()->json(['status' => false, 'message' => 'Aucune photo fournie.']);
        }

        $file = $request->file('photo');
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        if (!$disk->exists('candidats')) {
            $disk->makeDirectory('candidats');
        }

        // Supprimer ancienne photo
        $candidat = \App\Models\Candidat::find($id);
        if ($candidat && $candidat->photo) {
            $disk->delete($candidat->photo);
        }

        $uuid = \Illuminate\Support\Str::uuid();
        if (extension_loaded('gd')) {
            $mime = $file->getMimeType();
            $src  = match($mime) {
                'image/jpeg','image/jpg' => imagecreatefromjpeg($file->getRealPath()),
                'image/png'              => imagecreatefrompng($file->getRealPath()),
                'image/webp'             => imagecreatefromwebp($file->getRealPath()),
                default                  => null,
            };
            if ($src) {
                $w = imagesx($src); $h = imagesy($src);
                if ($w > 400) {
                    $r = 400/$w; $nw = 400; $nh = (int)($h*$r);
                    $img = imagecreatetruecolor($nw, $nh);
                    imagecopyresampled($img, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
                    imagedestroy($src);
                } else { $img = $src; }
                $tmp = sys_get_temp_dir().'/'.$uuid.'.webp';
                imagewebp($img, $tmp, 85);
                imagedestroy($img);
                $path = 'candidats/'.$uuid.'.webp';
                $disk->put($path, file_get_contents($tmp));
                @unlink($tmp);
            } else {
                $ext  = $file->getClientOriginalExtension() ?: 'jpg';
                $path = 'candidats/'.$uuid.'.'.$ext;
                $disk->put($path, file_get_contents($file->getRealPath()));
            }
        } else {
            $ext  = $file->getClientOriginalExtension() ?: 'jpg';
            $path = 'candidats/'.$uuid.'.'.$ext;
            $disk->put($path, file_get_contents($file->getRealPath()));
        }

        \App\Models\Candidat::where('id', $id)->update(['photo' => $path]);

        return response()->json([
            'status'    => true,
            'photo_url' => 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/'.$path,
            'message'   => 'Photo mise à jour.',
        ]);
    }

}
