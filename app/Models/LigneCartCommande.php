<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCartCommande extends Model
{
    use HasFactory;
    protected $fillable = [
        'qte',
        'id_produit',
        'id_cart_commande',
        'code_produit',
        'desc_produit',
        'prix_produit',
    ];
}
