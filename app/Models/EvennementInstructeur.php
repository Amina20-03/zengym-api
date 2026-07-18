<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvennementInstructeur extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_validation',
        'evennement_id',
        'user_id',

    ];
}
