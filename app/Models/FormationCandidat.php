<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationCandidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'methode_paiement',
        'date_validation',
        'paiement_status',
        'ref',
        'formation_id',
        'user_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
