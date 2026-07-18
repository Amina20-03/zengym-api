<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = 'articles';
    protected $fillable = [
        'categ_id',
        'titre',
        'contenu',
        'photo',
        'ordre',
        'active',
    ];

    public function categorie()
    {
        return $this->belongsTo(ArticleCategorie::class, 'categ_id');
    }
}
