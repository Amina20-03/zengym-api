<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoixQuest extends Model
{
    use HasFactory;
    protected $fillable = [
        'rep',
        'status',
        'id_question',
    ];
}
