<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategCandidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'titre',
        'desc',
    ];
}
