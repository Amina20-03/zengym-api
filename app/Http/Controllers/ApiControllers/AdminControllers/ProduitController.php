<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CategProduit;
use App\Models\Instructeur;
use App\Models\LigneVenteProd;
use App\Models\Produit;
use App\Models\CartCommande;
use App\Models\LigneCartCommande;
use App\Models\User;
use App\Models\VenteProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ZenGymConfirmationEmail;
class ProduitController extends Controller
{
    public function index_categ_produit(){
        $liste = CategProduit::orderBy('id','desc')->get();

        return response()->json([
            "status" => true,
            'liste' => $liste,
            "message" => '',
        ]);
    }
    public function add_categ_produit(Request $request){
        $max_id = CategProduit::max('id');
        $code = 'CP_00'.$max_id;
        CategProduit::create([
            'code'=>$code,
            'lib'=>$request->lib,
            'desc'=>$request->desc,
        ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_categ_produit($id,Request $request){
        $detail = CategProduit::where('id',$id)->get();
        return response()->json([
            "status" => true,
            "detail" => $detail,
            "message" => '',
        ]);
    }
    public function update_categ_produit($id,Request $request){
        CategProduit::where('id',$id)
            ->update([
                'lib'=>$request->lib,
                'desc'=>$request->desc,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_categ_produit(Request $request){
        $id = $request->champ_id;
        Produit::where('categ_produit_id',$id)
            ->delete();
        CategProduit::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_produit(){
        $produitlist =[];
        $liste = Produit::orderBy('id','desc')->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($produitlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'couleur'=>$liste[$j]->couleur,
                        'dosage'=>$liste[$j]->dosage,
                        'prix_vente_ht'=>$liste[$j]->prix_vente_ht,
                        'prix_vente_net_ht'=>$liste[$j]->prix_vente_net_ht,
                        'prix_vente_ttc'=>$liste[$j]->prix_vente_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'code_barre'=>$liste[$j]->code_barre,
                        'photo'=>$liste[$j]->photo,
                        'max_remise'=>$liste[$j]->max_remise,
                        'active'=>$liste[$j]->active,
                        'categ_produit_desc'=>CategProduit::where('id',$liste[$j]->categ_produit_id)->value('lib'),

                    ]);
            }
        }

        $list_cat = CategProduit::all();
        return response()->json([
            "status" => true,
            'liste' => $produitlist,
            'list_cat' => $list_cat,
            "message" => '',
        ]);
    }
    public function add_produit(Request $request){
        $max_id = Produit::max('id');
        $code = 'P_00'.$max_id;
        Produit::create([
            'code'=>$code,
            'photo'=>$request->photo,
            'desc'=>$request->desc,
            'couleur'=>$request->couleur,
            'dosage'=>$request->dosage,
            'prix_vente_ht'=>$request->prix_vente_ht,
            'prix_vente_net_ht'=>$request->prix_vente_net_ht,
            'prix_vente_ttc'=>$request->prix_vente_ttc,
            'taux_tva'=>$request->taux_tva,
            'max_remise'=>$request->max_remise,
            'active'=>$request->active,
            'categ_produit_id'=>$request->categ_produit_id,
        ]);

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function edit_produit($id,Request $request){
        $detail = Produit::where('id',$id)->get();
        $list_cat = CategProduit::all();
        $desc_cat = CategProduit::where('id',$detail[0]->categ_produit_id)->value('lib');

        return response()->json([
            "status" => true,
            "detail" => $detail,
            'list_cat' => $list_cat,
            'desc_cat' => $desc_cat,
            "message" => '',
        ]);
    }
    public function update_produit($id,Request $request){
        Produit::where('id',$id)
            ->update([
                'photo'=>$request->photo,
                'desc'=>$request->desc,
                'couleur'=>$request->couleur,
                'dosage'=>$request->dosage,
                'prix_vente_ht'=>$request->prix_vente_ht,
                'prix_vente_net_ht'=>$request->prix_vente_net_ht,
                'prix_vente_ttc'=>$request->prix_vente_ttc,
                'taux_tva'=>$request->taux_tva,
                'max_remise'=>$request->max_remise,
                'active'=>$request->active,
                'categ_produit_id'=>$request->categ_produit_id,
            ]);
        return response()->json([
            "status" => true,
            "message" => '',
        ]);

    }
    public function delete_produit(Request $request){
        $id = $request->champ_id;
        Produit::where('id',$id)
            ->delete();
        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function index_produit_shop(){
        $produitlist =[];
        $list_cat = CategProduit::all();
        $liste = [];
        if(count($list_cat)>0){
            $liste = Produit::where('categ_produit_id',$list_cat[0]->id)
                ->where('active',true)
                ->orderBy('id','desc')
                ->get();
        }

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($produitlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'couleur'=>$liste[$j]->couleur,
                        'dosage'=>$liste[$j]->dosage,
                        'prix_vente_ht'=>$liste[$j]->prix_vente_ht,
                        'prix_vente_net_ht'=>$liste[$j]->prix_vente_net_ht,
                        'prix_vente_ttc'=>$liste[$j]->prix_vente_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'code_barre'=>$liste[$j]->code_barre,
                        'photo'=>$liste[$j]->photo,
                        'max_remise'=>$liste[$j]->max_remise,
                        'active'=>$liste[$j]->active,
                        'categ_produit_desc'=>CategProduit::where('id',$liste[$j]->categ_produit_id)->value('lib'),

                    ]);
            }
        }

      
        return response()->json([
            "status" => true,
            'liste' => $produitlist,
            'list_cat' => $list_cat,
            'id_cat' => $list_cat[0]->id ?? '',
            "message" => '',
        ]);
    }
    public function produit_by_categ($id_cat){
        $produitlist =[];
        $list_cat = CategProduit::all();
        $liste = Produit::where('categ_produit_id',$id_cat)
            ->where('active',true)
            ->orderBy('id','desc')
            ->get();

        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($produitlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'couleur'=>$liste[$j]->couleur,
                        'dosage'=>$liste[$j]->dosage,
                        'prix_vente_ht'=>$liste[$j]->prix_vente_ht,
                        'prix_vente_net_ht'=>$liste[$j]->prix_vente_net_ht,
                        'prix_vente_ttc'=>$liste[$j]->prix_vente_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'code_barre'=>$liste[$j]->code_barre,
                        'photo'=>$liste[$j]->photo,
                        'max_remise'=>$liste[$j]->max_remise,
                        'active'=>$liste[$j]->active,
                        'categ_produit_desc'=>CategProduit::where('id',$liste[$j]->categ_produit_id)->value('lib'),

                    ]);
            }
        }

      
        return response()->json([
            "status" => true,
            'liste' => $produitlist,
            'list_cat' => $list_cat,
            'id_cat' => $id_cat,
            "message" => '',
        ]);
    }
    public function detail_produit($id_prod){
        $produitlist =[];
        $liste = Produit::where('id',$id_prod)->get();
        if (count($liste)>0) {
            for ($j=0; $j <count($liste) ; $j++) {
                array_push($produitlist,
                    [
                        'id'=>$liste[$j]->id,
                        'code'=>$liste[$j]->code,
                        'desc'=>$liste[$j]->desc,
                        'couleur'=>$liste[$j]->couleur,
                        'dosage'=>$liste[$j]->dosage,
                        'prix_vente_ht'=>$liste[$j]->prix_vente_ht,
                        'prix_vente_net_ht'=>$liste[$j]->prix_vente_net_ht,
                        'prix_vente_ttc'=>$liste[$j]->prix_vente_ttc,
                        'taux_tva'=>$liste[$j]->taux_tva,
                        'code_barre'=>$liste[$j]->code_barre,
                        'photo'=>$liste[$j]->photo,
                        'max_remise'=>$liste[$j]->max_remise,
                        'active'=>$liste[$j]->active,
                        'categ_produit_desc'=>CategProduit::where('id',$liste[$j]->categ_produit_id)->value('lib'),

                    ]);
            }
        }
        return response()->json([
            "status" => true,
            'liste' => $produitlist,
            "message" => '',
        ]);
    }
    public function cart_store(Request $request, $product_id)
    {
        $cart = Cart::where('user_id', $request->user_id)->where('product_id', $product_id)->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $request->user_id,
                'product_id' => $product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => '',
        ]);
    }
    public function cart_index($user_id)
    {
        $cartItems = Cart::where('user_id',$user_id)->with('product')->get();
        return response()->json([
            "status" => true,
            "message" => '',
            "cartItems" => $cartItems,
        ]);
    }

    public function payer(Request $request)
    {
        $list_produits_id = explode("|", $request->list_produits_id);
        $list_produits_desc = explode("|", $request->list_produits_desc);
        $list_produits_prix = explode("|", $request->list_produits_prix);
        $list_produits_qte = explode("|", $request->list_produits_qte);
        $status = true;
        CartCommande::create([
            "code"=>$request->code,
            "prix_total"=>$request->prix_total_input,
            "date"=>date('Y-m-d'),
            "paiement_par"=>$request->paiement_par,
            "paiement_status"=>$request->paiement_status,
            "ref"=>$request->ref,
            "user_id"=>$request->user_id_input,
        ]);
        if ($request->code_instr != '0'){
            $instructeur_id = Instructeur::where('code_instr',$request->code_instr)->value('id');
            VenteProd::create([
                'code'=>$request->code,
                'date'=>date('Y-m-d'),
                'tot_ttc'=>$request->prix_total_input,
                'instructeur_id'=>$instructeur_id,
                "paiement_par"=>$request->paiement_par,
                "paiement_status"=>$request->paiement_status,
                'encaisse'=>false,
                'created_at' => now(),

            ]);
        }

        if ($list_produits_id != null) {

            if (count($list_produits_id) > 1) {
                for ($i = 0; $i < count($list_produits_id); $i++) {

                    if ($list_produits_id[$i] != "") {
                        LigneCartCommande::create([
                            'qte'=>$list_produits_qte[$i],
                            'id_produit'=>$list_produits_id[$i],
                            'id_cart_commande'=>CartCommande::max('id'),
                            'desc_produit'=>$list_produits_desc[$i],
                            'prix_produit'=>$list_produits_prix[$i],
                        ]);
                        if ($request->code_instr != '0'){
                            LigneVenteProd::create([
                                'qte'=>$list_produits_qte[$i],
                                'pu_vente'=>$list_produits_prix[$i],
                                'prod_id'=>$list_produits_id[$i],
                                'vente_prod_id'=>VenteProd::max('id'),
                                'created_at' => now(),
                            ]);
                        }

                    }
                    if($request->paiement_status){
                        Cart::where('product_id',$list_produits_id[$i])
                            ->where('user_id',$request->user_id_input)
                            ->delete();
                    }
                    
                }
            }
        }


        return response()->json([
            "status" => $status,
            "message" => '',
        ]);
    }
    public function cart_destroy($productId)
    {
        $cart = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
        if ($cart) {
            $cart->delete();
        }

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
    public function inscription(Request $request)
    {
        $status=false;
        $message='';
        if($request->mail != null){
            $verif = User::where('mail', $request->mail)->where('role','PASSAGER')
                ->count();
            if($verif>0){
                $status=false;
                $message='content.Email_existe_déjà';
            }
            else{
                $pass = $this->generateRandomPassword(12);
                User::create([
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'mail'=>$request->mail,
                    'adresse'=>$request->adresse,
                    'tel'=>$request->tel,
                    'role'=>'PASSAGER',
                    'email'=>$request->mail,
                    'password'=>Hash::make($pass),
                ]);

                $data_mail = [
                    'lien' => 'https://zengymhealth.com/login/',
                    'email' => $request->mail,
                    'password' => $pass,
                ];

                // Envoyer l'e-mail
                Mail::to($request->mail)->send(new ZenGymConfirmationEmail($data_mail));





                $token = Auth::guard('api')->attempt(['email' => $request->mail, 'password' => $pass]);

                if (!$token) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized',
                    ], 401);
                }

                $user = Auth::guard('api')->user();
                if ($request->cart){
                    if (count($request->cart)>0){
                        // Cart::where('user_id',$user->id)->delete();
                        foreach ($request->cart as $item){
                            $verif = Cart::where('user_id',$user->id)->where('product_id',$item['product_id'])
                                ->get();
                            if (count($verif)>0){
                                Cart::where('id',$verif[0]->id)
                                    ->update([
                                        'quantity'=>intval($verif[0]->quantity)+intval($item['quantity']),
                                    ]);
                            }
                            else{
                                Cart::create([
                                    'user_id' => $user->id,
                                    'product_id' => $item['product_id'],
                                    'quantity' => $item['quantity'],
                                ]);
                            }
                        }
                    }
                }
                $my_products = Cart::where('user_id',$user->id)->with('product')->get();
                $status=true;
                $message='content.Ajout_terminée';
            }
        }

        return response()->json([
            "status" => $status,
            "message" => $message,
            'user' => $user,
            'my_products' => $my_products,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}
