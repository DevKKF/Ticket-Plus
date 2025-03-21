<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table            = "region";
    protected $primaryKey       = "region_id";
    public $timestamps          = false;

    protected $fillable = [
        'region_nom',
        'region_statut',
        'region_datecrea',
        'region_datemodif'
    ];
}
