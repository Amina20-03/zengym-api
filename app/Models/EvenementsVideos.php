<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementsVideos extends Model
{
    use HasFactory;
    protected $fillable = [
        'lib',
        'path',
        'event_id',

    ];
}
