<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideos extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'titre',
        'desc',
        'categ_id',
        'user_id',
    ];
}
