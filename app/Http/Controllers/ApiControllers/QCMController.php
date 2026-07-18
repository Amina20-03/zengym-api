<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\ChoixQuest;
use App\Models\DocumentInstructeurCategorie;
use App\Models\Examen;
use App\Models\ExamenUser;
use App\Models\PhotoInstructeurCategorie;
use App\Models\Question;
use App\Models\QuestUser;
use App\Models\UserChoixSelected;
use App\Models\VideoInstructeurCategorie;
use Illuminate\Http\Request;

class QCMController extends Controller
{
    public function examen_page() {
        $examen= Examen::where('id',1)->get();
        $ExamenUser_Check = ExamenUser::where('id_examen',1)
            ->where('id_user',auth()->user()->id)
            ->get();
        if(count($ExamenUser_Check)==0){
            ExamenUser::create([
                'id_examen' => 1,
                'id_user' => auth()->user()->id,
                'score' => '0',
            ]);
        }
        $questions = Question::where('id_examen',1)->get();
        $choix_quest = ChoixQuest::all();
        return response()->json([
            "status" => true,
            "message" => '',
            "examen" => $examen,
            "questions" => $questions,
            "choix_quest" => $choix_quest,
            "ExamenUser_Check" => $ExamenUser_Check,

        ]);
    }
    public function verifier_la_bonne_reponse(Request $request) {

        $choix_selected_check = UserChoixSelected::where('id_user',auth()->user()->id)
            ->where('id_quest',$request->quest)
            ->get();
        if (count($choix_selected_check)==0) {
            UserChoixSelected::create([
                'id_quest' => $request->quest,
                'id_choix_selected' => $request->rep,
                'id_user' => auth()->user()->id,
            ]);
        }
        else{
            UserChoixSelected::where('id_user',auth()->user()->id)
                ->where('id_quest',$request->quest)
                ->update([
                    'id_choix_selected' => $request->rep,
                ]);
        }
        $correct_choix = ChoixQuest::where('id',$request->rep)->get();
        $QuestUser_Check = QuestUser::where('id_quest',$request->quest)
            ->where('id_user',auth()->user()->id)
            ->get();
        if ($correct_choix[0]->status){
            if(count($QuestUser_Check)==0){
                QuestUser::create([
                    'id_quest' => $request->quest,
                    'id_user' => auth()->user()->id,
                    'status_rep' => true,
                ]);
            }
            else{
                QuestUser::where('id_user',auth()->user()->id)
                    ->where('id_quest',$request->quest)
                    ->update([
                        'status_rep' => true
                    ]);
            }
        }
        else{
            if(count($QuestUser_Check)==0){
                QuestUser::create([
                    'id_quest' => $request->quest,
                    'id_user' => auth()->user()->id,
                    'status_rep' => false,
                ]);
            }
            else{
                QuestUser::where('id_user',auth()->user()->id)
                    ->where('id_quest',$request->quest)
                    ->update([
                        'status_rep' => false
                    ]);
            }
        }
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function valider_examen(Request $request) {
        $id_examen = $request->id_examen;
        $questions = Question::where('id_examen', $id_examen)->get();

        $point_true_rep = 0;
        $res_examen = "";
        $commentaire = "";

        foreach ($questions as $question) {
            $QuestUser = QuestUser::where('id_quest', $question->id)
                ->where('id_user', auth()->user()->id)
                ->first(); // Retourne un seul résultat ou null

            if ($QuestUser && $QuestUser->status_rep) {
                $point_true_rep += $question->point;
            }
        }

        if ($point_true_rep >= 15) {
            $res_examen = "Formation valide";
        } elseif ($point_true_rep >= 10 && $point_true_rep <= 14) {
            $res_examen = "Formation non valide";
            $commentaire = "Possibilité de reprise après un accompagnement personnalisé avec un frais supplémentaire de 10% du coût de la formation";
        } elseif ($point_true_rep < 10) {
            $res_examen = "Formation échouée";
            $commentaire = "Reprise complète recommandée avec un frais supplémentaire de 50% du coût de la formation";
        }

        ExamenUser::where('id_examen', $id_examen)
            ->where('id_user', auth()->user()->id)
            ->update([
                'score' => $point_true_rep,
                'res_examen' => $res_examen,
                'commentaire' => $commentaire,
            ]);

        return response()->json([
            "status" => true,
            "message" => '',
            "score" => $point_true_rep,
            "res_examen" => $res_examen,
            "commentaire" => $commentaire,
            "examen" => Examen::where('id', $id_examen)->first(),
        ]);
    }
    public function create_certif(Request $request)
    {
        ExamenUser::where('id_examen',$request->id_examen)
            ->where('id_user',auth()->user()->id)
            ->update([
                'certificat' => $request->image,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
            "examen" => Examen::where('id',$request->id_examen)->get(),
        ]);
    }
}
