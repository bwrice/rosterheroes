<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Attacks\AbstractCombatAttack;
use Illuminate\Support\Collection;

class AbstractCombatAttackCollection extends Collection
{
    public function filterByAttackerPositions(Collection $combatPositions)
    {
        return $this->filter(function (AbstractCombatAttack $combatAttack) use ($combatPositions) {
            return in_array($combatAttack->getAttackerPosition()->id, $combatPositions->pluck('id')->toArray());
        });
    }

    public function ready()
    {
        return $this->filter(function (AbstractCombatAttack $combatAttack) {
            $rand = rand(1, 100);
            return $combatAttack->getCombatSpeed() >= $rand;
        });
    }
}
