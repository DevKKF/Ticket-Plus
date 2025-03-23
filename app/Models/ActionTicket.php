<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionTicket extends Model
{
    use HasFactory;

    protected $table            = "action_ticket";
    protected $primaryKey       = "action_ticket_id";
    public $timestamps          = false;
}
