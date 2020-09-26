<?php


namespace App\Facades;


use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Models\CombatPosition;
use App\Services\Models\Reference\CombatPositionService;
use Illuminate\Support\Facades\Facade;

/**
 * Class CombatPositionFacade
 * @package App\Facades
 *
 * @method static CombatPositionBehavior getBehavior(int|string $identifier)
 * @method static CombatPosition closestProximity(array $combatPositionIDs)
 *
 * @see CombatPositionService
 */
class CombatPositionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CombatPositionService::class;
    }
}
