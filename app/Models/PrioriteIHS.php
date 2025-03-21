<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioriteIHS extends Model
{
    use HasFactory;

    protected $table            = "priorite_ihs";
    protected $primaryKey       = "priorite_ihs_id";
    public $timestamps          = false;

    protected $fillable = [
        'priorite_ihs_nom',
        'priorite_ihs_statut',
        'priorite_ihs_datecrea',
        'priorite_ihs_datemodif'
    ];
}
