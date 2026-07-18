<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBonSortie extends Model
{
    use HasFactory;
    protected $fillable = [
        'prod_id',
        'qte_sortie',
        'pu_sortie',
        'total_ligne_st',
        'IDBSORTIE',
    ];
}
