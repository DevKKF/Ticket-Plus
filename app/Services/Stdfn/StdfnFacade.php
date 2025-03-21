<?php
namespace App\Services\Stdfn;

use Illuminate\Support\Facades\Facade;

class StdfnFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Stdfn::class;
    }
}