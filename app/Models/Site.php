<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table            = "site";
    protected $primaryKey       = "site_id";
    public $timestamps          = false;

    protected $fillable = [
        'site_ihs',
        'site_nom',
        'region_id',
        'zone_id',
        'operateur_id',
        'priorite_ihs_id',
        'topologie_typologie_id',
        'site_sbc',
        'site_statut',
        'site_date_creation',
        'site_datecrea',
        'site_datemodif',
        'creerpar_id',
        'modifierpar_id'
    ];
}
