<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_validation',
        'formation_id',
        'user_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
