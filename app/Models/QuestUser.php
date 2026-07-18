<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_quest',
        'id_user',
        'status_rep',
    ];
}
