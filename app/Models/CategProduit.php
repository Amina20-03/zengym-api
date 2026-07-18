<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategProduit extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'lib',
        'desc',
    ];
}
