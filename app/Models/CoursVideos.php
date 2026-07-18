<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursVideos extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'titre',
        'desc',
        'cours_id',

    ];
}
