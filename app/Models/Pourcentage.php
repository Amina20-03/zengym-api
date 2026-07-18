<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pourcentage extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_client',
        'pr_prod',
        'pr_formation',
        'cat_inst_id',
    ];
}
