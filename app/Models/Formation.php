<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date',
        'heure',
        'nbr_participant',
        'titre',
        'sujet',
        'desc',
        'frais',
        'devise',
        'lieu',
        'nbr_place_max',
        'contact',
        'categ_formation_id',
        'instructeur_id',
        'organisateur_id',
        'user_id',
        'approuver',
        'realiser',
        'encaisse',
        'enligne',
        'photo_principale',
        'path_livret_scientifique',
        'path_presentation_power_point',
        'path_video_basicone',
        'path_prog_de_formation',
        'enligne_url',
    ];
    public function candidats()
    {
        return $this->hasMany(FormationCandidat::class);
    }

    public function utilisateurs()
    {
        return $this->hasMany(FormationUser::class);
    }
    public function photos()
    {
        return $this->hasMany(FormationPhotos::class);
    }
    public function videos()
    {
        return $this->hasMany(FormationVideos::class);
    }
    public function documents()
    {
        return $this->hasMany(FormationDocument::class);
    }
    public function audios()
    {
        return $this->hasMany(FormationAudio::class);
    }
}
