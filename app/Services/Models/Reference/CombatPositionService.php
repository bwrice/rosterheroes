<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\TargetRanges\BackLineBehavior;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Behaviors\TargetRanges\FrontLineBehavior;
use App\Domain\Behaviors\TargetRanges\HighGroundBehavior;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CombatPositionService
 * @package App\Services\Models\Reference
 *
 * @method CombatPositionBehavior getBehavior($identifier)
 */
class CombatPositionService extends ReferenceService
{

    public function __construct()
    {
        $this->behaviors[CombatPosition::FRONT_LINE] = app(FrontLineBehavior::class);
        $this->behaviors[CombatPosition::BACK_LINE] = app(BackLineBehavior::class);
        $this->behaviors[CombatPosition::HIGH_GROUND] = app(HighGroundBehavior::class);
    }

    protected function all(): Collection
    {
        return CombatPosition::all();
    }

    /**
     * @param array $combatPositionIDs
     * @return CombatPosition
     */
    public function closestProximity(array $combatPositionIDs = null)
    {
        $referenceModels = $combatPositionIDs ? $this->mapIDsToModels($combatPositionIDs) : $this->getReferenceModels();
        return $referenceModels->sortBy(function (CombatPosition $combatPosition) {
            return $combatPosition->getBehavior()->getProximity();
        })->first();
    }

    public function proximity(int $combatPositionID)
    {
        return $this->getBehavior($combatPositionID)->getProximity();
    }

}
