<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table            = "zone";
    protected $primaryKey       = "zone_id";
    public $timestamps          = false;

    protected $fillable = [
        'zone_nom',
        'region_id',
        'zone_statut',
        'zone_datecrea',
        'zone_datemodif'
    ];
}
