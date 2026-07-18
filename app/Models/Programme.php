<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $table = 'programmes';
    protected $fillable = [
        'instructeur_id',
        'titre',
        'description',
        'duree_semaines',
        'niveau',
        'photo',
        'actif',
    ];
}
