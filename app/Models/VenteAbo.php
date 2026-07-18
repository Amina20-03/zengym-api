<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteAbo extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date',
        'montant_ht',
        'montant_ttc',
        'taux_tva',
        'paiement',
        'solder',
        'dernier',
        'date_deb',
        'date_fin',
        'type_abo_id',
        'instructeur_id',
    ];
}
