<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAbo extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'desc',
        'nb_mois',
        'prix_ttc',
        'taux_tva',
        'prix_ht',
        'categ_abo_id',
    ];
}
