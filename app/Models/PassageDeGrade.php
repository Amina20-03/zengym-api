<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassageDeGrade extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'des',
        'nbr_event',
        'nbr_masterclass',
        'categ_instructeur_id_1',
        'categ_instructeur_id_2',

    ];
}
