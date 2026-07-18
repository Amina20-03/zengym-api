<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\CategInstructeur;
use App\Models\Instructeur;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        // Chercher d'abord un user CANDIDAT avec cet email
        // (évite les conflits quand plusieurs users ont le même email)
        $userCandidat = \App\Models\User::where('email', $request->email)
            ->whereNotNull('candidat_id')
            ->first();

        if ($userCandidat && \Illuminate\Support\Facades\Hash::check($request->password, $userCandidat->password)) {
            $token = Auth::guard('api')->login($userCandidat);
        } else {
            $token = Auth::guard('api')->attempt($credentials);
        }

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user()->load('abonnements');
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
        $instructeur_detail = Instructeur::where('id',$user->instructeur_id)->get();
        return response()->json([
            'status' => true,
            'user' => $user,
            'categorie_instructeur' => CategInstructeur::where('id',$instructeur_detail[0]->categ_instructeur_id??'')->value('desc'),
            'my_products' => $my_products,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    // User Profile (GET)
    public function profile(){

        $userdata = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
    }

    // To generate refresh token value
    public function refreshToken(){

        $newToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $newToken
        ]);
    }


    // User Logout (GET)
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

}
