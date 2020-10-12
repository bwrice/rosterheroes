<?php


namespace App\Facades;


use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Models\CombatPosition;
use App\Services\Models\Reference\CombatPositionService;
use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class CombatPositionFacade
 * @package App\Facades
 *
 * @method static CombatPositionBehavior getBehavior(int|string $identifier)
 * @method static CombatPosition closestProximity(array $combatPositionIDs)
 * @method static int proximity(int|string $identifier)
 * @method static int id(string $combatPositionName);
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
