<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteProd extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date',
        'tot_ht',
        'tot_ttc',
        'instructeur_id',
        'encaisse',
        'paiement_par',
        'paiement_status',

    ];
}
