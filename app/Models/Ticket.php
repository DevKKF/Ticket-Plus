<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table            = "ticket";
    protected $primaryKey       = "ticket_id";
    public $timestamps          = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Créer par
    public function enregistrer_par()
    {
        return $this->belongsTo(User::class, 'creerpar_id');
    }

    //Modifier par
    public function modifier_par()
    {
        return $this->belongsTo(User::class, 'modifierpar_id');
    }
}
