<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
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
        $targets = $combatants->filter(function (Combatant $combatant) use ($attackTargetPositionID) {
            return $combatant->getCombatPositionID() === $attackTargetPositionID;
        });

        return $targets->take($combatAttack->getMaxTargetsCount());
    }
}
