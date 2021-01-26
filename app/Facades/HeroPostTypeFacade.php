<?php


namespace App\Facades;


use App\Domain\Models\Squad;
use App\Services\Models\Reference\HeroPostTypeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class HeroPostTypeFacade
 * @package App\Facades
 *
 * @method static Collection cheapestForSquad(Squad $squad)
 * @method static Collection squadStarting()
 * @method static int squadStartingCount(string $heroPostTypeName)
 *
 * @see HeroPostTypeService
 */
class HeroPostTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HeroPostTypeService::class;
    }
}
