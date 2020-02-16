<?php


namespace App\Facades;


use App\Domain\Interfaces\HasFantasyPoints;
use Illuminate\Support\Facades\Facade;

/**
 * Class FantasyPower
 * @package App\Facades
 *
 * @method static float calculate(HasFantasyPoints $hasFantasyPoints)
 *
 * @see \App\Services\FantasyPower
 */
class FantasyPower extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fantasy-power';
    }
}
