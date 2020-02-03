<?php


namespace App\Domain\Combat;


use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

class CombatantCollection extends Collection
{
    public function sortByTargetPriority(TargetPriority $targetPriority)
    {
        return $this;
    }

    public function filterByCombatPositions(Collection $combatPositions)
    {
        return $this;
    }
}
