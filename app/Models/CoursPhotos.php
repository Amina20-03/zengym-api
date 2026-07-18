<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursPhotos extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'titre',
        'desc',
        'cours_id',

    ];
}
