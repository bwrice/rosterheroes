<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Attacks\CombatAttack;
use Illuminate\Support\Collection;

class CombatAttackCollection extends Collection
{
    public function withinAttackerProximity(int $proximity)
    {
        return $this->filter(function (CombatAttack $combatAttack) use ($proximity) {
            return $combatAttack->getAttackerPosition()->isWithinProximity($proximity);
        });
    }

    public function filterByAttackerPositions(Collection $combatPositions)
    {
        return $this->filter(function (CombatAttack $combatAttack) use ($combatPositions) {
            return in_array($combatAttack->getAttackerPosition()->id, $combatPositions->pluck('id')->toArray());
        });
    }

    public function ready()
    {
        return $this->filter(function (CombatAttack $combatAttack) {
            // Use 10,000 and multiply speed by 100 so precision up to 2 decimals on combat speed matters
            $rand = rand(1, 10000);
            return ($combatAttack->getCombatSpeed() * 100) >= $rand;
        });
    }
}
