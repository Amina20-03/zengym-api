<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallerie extends Model
{
    use HasFactory;

    protected $table = 'gallerie';

    protected $fillable = [
        'titre',
        'description',
        'photo',
        'categ_id',
        'ordre',
        'active',
    ];

    public function categorie()
    {
        return $this->belongsTo(PhotoInstructeurCategorie::class, 'categ_id');
    }
}
