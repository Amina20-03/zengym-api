<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructeur extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_instr',
        'profession',
        'commentaire',
        'sexe',
        'date_naiss',
        'photo',
        'cin',
        'pays_id',
        'diplome',
        'categ_instructeur_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'instructeur_id');
    }
}
