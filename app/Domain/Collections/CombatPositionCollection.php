<?php


namespace App\Domain\Collections;


use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class CombatPositionCollection extends Collection
{
    /**
     * @return CombatPosition|null
     */
    public function closestProximity()
    {
        return $this->sortBy(function (CombatPosition $combatPosition) {
            return $combatPosition->getBehavior()->getProximity();
        })->first();
    }
}
