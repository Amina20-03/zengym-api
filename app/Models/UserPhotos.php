<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhotos extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'titre',
        'desc',
        'categ_id',
        'user_id',
    ];
}
