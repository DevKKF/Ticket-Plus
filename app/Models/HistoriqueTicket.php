<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueTicket extends Model
{
    use HasFactory;

    protected $table            = "historique_ticket";
    protected $primaryKey       = "historique_ticket_id";
    public $timestamps          = false;

    
    //Créer par
    public function enregistrer_par()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Modifier par
    public function modifier_par()
    {
        return $this->belongsTo(User::class, 'modifierpar_id');
    }
}
