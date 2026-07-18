<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LangueEvennement extends Model
{
    use HasFactory;
    protected $fillable = [
        'langue',
        'evennement_id',
    ];
}
