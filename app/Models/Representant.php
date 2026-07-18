<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representant extends Model
{
    use HasFactory;
    protected $fillable = [
        'raison_social',
        'contact',
        'mf',
        'rc',
        'localisation',
        'categ_rep_id',

    ];
}
