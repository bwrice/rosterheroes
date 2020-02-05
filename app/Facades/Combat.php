<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Combat
 * @package App\Facades
 *
 * @see \App\Services\Combat
 */
class Combat extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'combat';
    }
}
