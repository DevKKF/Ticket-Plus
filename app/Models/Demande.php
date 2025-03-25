<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $table            = "demande";
    protected $primaryKey       = "demande_id";
    public $timestamps          = false;

    //Créer par
    public function enregistrer_par()
    {
        return $this->belongsTo(User::class, 'creerpar_id');
    }

    //Consulter par
    public function consulter_par()
    {
        return $this->belongsTo(User::class, 'consulterpar_id');
    }

    //Valider par
    public function valider_par()
    {
        return $this->belongsTo(User::class, 'validerpar_id');
    }

    //Annuler par
    public function annuler_par()
    {
        return $this->belongsTo(User::class, 'annulerpar_id');
    }
}
