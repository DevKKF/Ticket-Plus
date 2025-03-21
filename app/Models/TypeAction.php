<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAction extends Model
{
    use HasFactory;

    protected $table            = "type_action";
    protected $primaryKey       = "type_action_id";
    public $timestamps          = false;
}
