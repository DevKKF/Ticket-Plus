<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    use HasFactory;

    protected $table            = "operateur";
    protected $primaryKey       = "operateur_id";
    public $timestamps          = false;

    protected $fillable = [
        'operateur_nom',
        'operateur_statut',
        'operateur_datecrea',
        'operateur_datemodif'
    ];
}
