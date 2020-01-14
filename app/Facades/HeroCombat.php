<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class HeroCombat extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hero-combat';
    }
}
