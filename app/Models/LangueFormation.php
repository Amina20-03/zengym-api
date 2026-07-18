<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LangueFormation extends Model
{
    use HasFactory;
    protected $fillable = [
        'langue',
        'formation_id',
    ];
}
