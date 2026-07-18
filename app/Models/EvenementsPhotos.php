<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementsPhotos extends Model
{
    use HasFactory;
    protected $fillable = [
        'lib',
        'photo',
        'event_id',

    ];
}
