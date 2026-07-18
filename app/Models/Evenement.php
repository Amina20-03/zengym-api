<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'desc',
        'fait',
        'frais',
        'devise',
        'date_deb',
        'date_fin',
        'heure_deb',
        'heure_fin',
        'nbr_participant',
        'nbr_place_dispo',
        'nbr_place_restant',
        'type_even_id',
        'instructeur_id',
        'titre',
        'sujet',
        'participant_a_levennement',
        'participant_non_inscrit',
        'salle',
        'contacte',
        'contact',
        'info_sur_lieu',
        'approuver',
        'pays_id',
        'affiche',
        'refuser',
    ];
}
