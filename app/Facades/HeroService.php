<?php


namespace App\Facades;


use App\Domain\Models\Hero;
use Illuminate\Support\Facades\Facade;

/**
 * Class HeroCombat
 * @package App\Facades
 *
 * @method static array combatUnReadyReasons(Hero $hero)
 * @method static bool combatReady(Hero $hero)
 *
 * @see \App\Services\Models\HeroService
 */
class HeroService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hero-service';
    }
}
