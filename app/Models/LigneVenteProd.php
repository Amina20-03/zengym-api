<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneVenteProd extends Model
{
    use HasFactory;
    protected $fillable = [
        'qte',
        'pu_vente',
        'pu_net_ht_vente',
        'remise',
        'prod_id',
        'vente_prod_id',
    ];
}
