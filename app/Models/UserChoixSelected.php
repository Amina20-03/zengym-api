<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChoixSelected extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_quest',
        'id_choix_selected',
        'id_user',
    ];
}
