<?php


namespace App\Domain\Collections;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

class CombatantCollection extends Collection
{
    public function sortByTargetPriority(TargetPriority $targetPriority)
    {
        return $this;
    }

    public function filterByCombatPosition(CombatPosition $combatPosition)
    {
        return $this;
    }
}
