<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCommande extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'prix_total',
        'ref',
        'date',
        'paiement_par',
        'paiement_status',
        'user_id',
    ];
}
