<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Facades\CombatPositionFacade;
use Illuminate\Support\Collection;

class GetReadyAttacksForCombatant
{
    protected GetClosestInheritedCombatPosition $getClosestInheritedCombatPosition;

    public function __construct(GetClosestInheritedCombatPosition $getClosestInheritedCombatPosition)
    {
        $this->getClosestInheritedCombatPosition = $getClosestInheritedCombatPosition;
    }

    /**
     * @param Combatant $combatant
     * @param Collection $combatants
     * @return Collection
     */
    public function execute(Combatant $combatant, Collection $combatants)
    {
        $combatPosition = $this->getClosestInheritedCombatPosition->execute($combatant, $combatants);
        $proximity = CombatPositionFacade::proximity($combatPosition->id);
        return $combatant->getCombatAttacks()->filter((function (CombatAttack $combatAttack) use ($proximity) {
            $attackProximity = CombatPositionFacade::proximity($combatAttack->getAttackerPositionID());
            if ($proximity > $attackProximity) {
                return false;
            }
            $combatSpeed = $combatAttack->getCombatSpeed();
            return rand(1, 10000) <= ceil($combatSpeed * 100);
        }));
    }
}
