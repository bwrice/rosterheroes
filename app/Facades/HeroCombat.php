<?php


namespace App\Facades;


use App\Domain\Models\Hero;
use Illuminate\Support\Facades\Facade;

/**
 * Class HeroCombat
 * @package App\Facades
 *
 * @method static bool ready(Hero $hero)
 */
class HeroCombat extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hero-combat';
    }
}
