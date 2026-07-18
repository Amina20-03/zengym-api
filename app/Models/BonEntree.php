<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonEntree extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_be',
        'date_be',
        'totah_ht_be',
        'total_ttc_be',
        'fournisseur_id',

    ];
}
