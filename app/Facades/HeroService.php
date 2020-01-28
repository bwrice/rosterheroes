<?php


namespace App\Facades;


use App\Domain\Models\Hero;
use Illuminate\Support\Facades\Facade;

/**
 * Class HeroCombat
 * @package App\Facades
 *
 * @method static array notReadyReasons(Hero $hero)
 * @method static bool combatUnReadyReasons(Hero $hero)
 *
 * @see \App\Services\ModelServices\HeroService
 */
class HeroService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hero-service';
    }
}
