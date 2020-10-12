<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Facades\CombatPositionFacade;
use Illuminate\Support\Collection;

class GetReadyAttacksForCombatant
{
    /**
     * @param Combatant $combatant
     * @param int $proximityID
     * @return Collection
     */
    public function execute(Combatant $combatant, int $proximityID)
    {
        $proximity = CombatPositionFacade::proximity($proximityID);
        return $combatant->getCombatAttacks()->filter((function (CombatAttack $combatAttack) use ($proximity) {
            $attackProximity = CombatPositionFacade::proximity($combatAttack->getAttackerPositionID());
            return $proximity <= $attackProximity;
        }));
    }
}
