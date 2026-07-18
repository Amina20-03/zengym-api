<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementCandidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'methode_paiement',
        'paiement_status',
        'ref',
        'date_validation',
        'event_id',
        'user_id',

    ];
}
