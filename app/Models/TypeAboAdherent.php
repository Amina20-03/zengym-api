<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAboAdherent extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'des',
        'periode',
        'frais',
        'remise',
        'frais_ap_remise',
        'seance_essai',
        'frais_seance_essai',
        'categ_abo_id',
        'nbr_pers_limit',
    ];
}
