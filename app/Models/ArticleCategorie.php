<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategorie extends Model
{
    use HasFactory;
    protected $table = 'article_categories';
    protected $fillable = ['code', 'desc'];
}
