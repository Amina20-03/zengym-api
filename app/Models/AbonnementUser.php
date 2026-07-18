<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonnementUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'date_paie',
        'ref',
        'status_paie',
        'type_paie',
        'date_deb',
        'date_fin',
        'active',
        'user_id',
        'type_abo_id',
        'vente_abo_id',
    ];
    public function instructeur()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
