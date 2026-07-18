<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'titre',
        'sujet',
        'desc',
        'frais',
        'devise',
        'date',
        'hdeb',
        'hfin',
        'emplacement',
        'categ_cours_id',
        'instructeur_id',
        'organisateur_id',
        'user_id',
        'approuver',
        'realiser',
    ];
}
