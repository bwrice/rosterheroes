<?php


namespace App\Facades;


use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Models\CombatPosition;
use App\Services\Models\Reference\CombatPositionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class CombatPositionFacade
 * @package App\Facades
 *
 * @method static CombatPositionBehavior getBehavior(int|string $identifier)
 * @method static CombatPosition closestProximity(array $combatPositionIDs = null)
 * @method static int proximity(int|string $identifier)
 * @method static int id(string $combatPositionName)
 * @method static Collection getReferenceModels()
 * @method static CombatPosition getReferenceModelByID(int $id)
 * @method static CombatPosition name(int $id)
 * @method static CombatPosition getReferenceModelByName(string $name)
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
