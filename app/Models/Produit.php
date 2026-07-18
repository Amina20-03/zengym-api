<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'desc',
        'couleur',
        'dosage',
        'prix_vente_ht',
        'prix_vente_net_ht',
        'prix_vente_ttc',
        'taux_tva',
        'code_barre',
        'photo',
        'max_remise',
        'active',
        'categ_produit_id',
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
