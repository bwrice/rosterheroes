<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Facades\CombatPositionFacade;
use Illuminate\Support\Collection;

class FindTargetsForAttack
{
    /**
     * @param CombatAttackInterface $combatAttack
     * @param Collection $combatants
     * @return Collection
     */
    public function execute(CombatAttackInterface $combatAttack, Collection $combatants)
    {
        $attackTargetPositionID = $combatAttack->getTargetPositionID();
        $possibleTargets = $combatants->filter(function (Combatant $combatant) use ($attackTargetPositionID) {
            return $combatant->getCombatPositionID() === $attackTargetPositionID;
        });

        if ($possibleTargets->isEmpty()) {
            /*
             * Group combatants by proximity and return those belonging
             * to the group with the closest proximity
             */
            $grouped = $combatants->groupBy(function (Combatant $combatant) {
                return CombatPositionFacade::proximity($combatant->getCombatPositionID());
            });

            $possibleTargets = $grouped->sortBy(function ($combatants, $proximity) {
                return $proximity;
            })->first();
        }

        return $possibleTargets->take($combatAttack->getMaxTargetsCount());
    }
}
