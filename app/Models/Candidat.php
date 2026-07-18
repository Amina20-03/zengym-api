<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'tel1',
        'tel2',
        'email',
        'adr',
        'cp',
        'mt_affiliation',
        'categ_candidat_id',
        'salle_sport_id',
        'instructeur_id',
        'photo',
    ];
}
