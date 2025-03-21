<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopologieTypologie extends Model
{
    use HasFactory;

    protected $table            = "topologie_typologie";
    protected $primaryKey       = "topologie_typologie_id";
    public $timestamps          = false;

    protected $fillable = [
        'topologie_typologie_nom',
        'topologie_typologie_statut',
        'topologie_typologie_datecrea',
        'topologie_typologie_datemodif'
    ];
}
