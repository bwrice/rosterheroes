<?php


namespace App\Facades;


use App\Domain\Models\Hero;
use Illuminate\Support\Facades\Facade;

/**
 * Class HeroCombat
 * @package App\Facades
 *
 * @method static array notReadyReasons(Hero $hero)
 * @method static bool readyForCombat(Hero $hero)
 *
 * @see \App\Services\HeroService
 */
class HeroService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hero-combat';
    }
}
