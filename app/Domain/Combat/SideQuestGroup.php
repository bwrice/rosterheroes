<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
use Illuminate\Support\Collection;

class SideQuestGroup implements CombatGroup
{
    public function getCombatMinions()
    {
        return collect();
    }

    public function getReadyAttacks(int $moment): Collection
    {
        // TODO: Implement getReadyAttacks() method.
    }

    public function getPossibleTargets($moment): CombatantCollection
    {
        // TODO: Implement getPossibleTargets() method.
    }
}
