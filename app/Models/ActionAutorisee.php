<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionAutorisee extends Model
{
    use HasFactory;

    protected $table            = "action_autorisee";
    protected $primaryKey       = "action_autorisee_id";
    public $timestamps          = false;
}
