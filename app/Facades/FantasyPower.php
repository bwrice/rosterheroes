<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class FantasyPower
 * @package App\Facades
 *
 * @method static float calculate(float $totalPoints)
 */
class FantasyPower extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fantasy-power';
    }
}
