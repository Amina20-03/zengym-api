<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBonEntree extends Model
{
    use HasFactory;
    protected $fillable = [
        'prod_id',
        'qte_entree',
        'pu_prod_entree',
        'total_ligne_entree',
        'bon_entree_id',

    ];
}
