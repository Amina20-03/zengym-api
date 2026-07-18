<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonSortie extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_bs',
        'date_bs',
        'total_ht_bs',
        'total_ttc_bs',
    ];
}
