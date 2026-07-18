<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationAudio extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'titre',
        'desc',
        'formation_id',

    ];
    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
