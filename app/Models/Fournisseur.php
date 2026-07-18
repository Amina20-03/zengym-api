<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = [
        'raison_soc_fourn',
        'contact_fourn',
        'tel1_fourn',
        'tel2_fourn',
        'mf_fourn',
        'rc_fourn',

    ];
}
