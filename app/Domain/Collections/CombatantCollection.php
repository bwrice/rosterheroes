<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Combatant;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

class CombatantCollection extends Collection
{
    public function sortByTargetPriority(TargetPriority $targetPriority)
    {
        $behavior = $targetPriority->getBehavior();
        $sortFunc = function (Combatant $combatant) use ($behavior) {
            return $behavior->getCombatantValue($combatant);
        };
        if ($behavior->sortCombatantsDescending()) {
            return $this->sortByDesc($sortFunc);
        } else {
            return $this->sortBy($sortFunc);
        }
    }

    public function filterByCombatPosition(CombatPosition $combatPosition)
    {
        return $this;
    }
}
