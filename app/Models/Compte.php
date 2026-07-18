<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date',
        'datedernmodif',
        'soldecpte',
        'instructeur_id',


    ];
}
