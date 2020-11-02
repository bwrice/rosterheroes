<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Facades\CombatPositionFacade;
use Illuminate\Support\Collection;

class GetReadyAttacksForCombatant
{
    protected GetClosestInheritedCombatPosition $getClosestInheritedCombatPosition;
    protected VerifyResourcesAvailable $verifyResourcesAvailable;

    public function __construct(
        GetClosestInheritedCombatPosition $getClosestInheritedCombatPosition,
        VerifyResourcesAvailable $verifyResourcesAvailable)
    {
        $this->getClosestInheritedCombatPosition = $getClosestInheritedCombatPosition;
        $this->verifyResourcesAvailable = $verifyResourcesAvailable;
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
        return $combatant->getCombatAttacks()->filter((function (CombatAttack $combatAttack) use ($combatant) {
            // Check combatant has resources
            return $this->verifyResourcesAvailable->execute($combatAttack->getResourceCosts(), $combatant);
        }))->filter((function (CombatAttack $combatAttack) use ($proximity) {
            // Check attack within combatant combat position range
            $attackProximity = CombatPositionFacade::proximity($combatAttack->getAttackerPositionID());
            return $attackProximity >= $proximity;
        }))->filter((function (CombatAttack $combatAttack) {
            // Check if attack is ready based on speed and randomness
            $combatSpeed = $combatAttack->getCombatSpeed();
            return rand(1, 10000) <= ceil($combatSpeed * 100);
        }));
    }
}
