<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviSante extends Model
{
    use HasFactory;
    protected $table = 'suivi_sante';
    protected $fillable = [
        'candidat_id',
        'date_suivi',
        'poids',
        'tour_de_taille',
        'tension_arterielle',
        'frequence_cardiaque',
        'glycemie',
        'saturation_oxygene',
        'niveau_energie',
        'niveau_stress',
        'qualite_sommeil',
        'presence',
        'commentaire',
    ];
}
