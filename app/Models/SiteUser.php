<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model
{
    use HasFactory;

    protected $table            = "site_user";
    protected $primaryKey       = "site_user_id";
    public $timestamps          = false;
}
