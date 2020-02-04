<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Combat
 * @package App\Facades
 *
 * @method static int getDamage($baseDamage, $damageMultiplier, $fantasyPower)
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
