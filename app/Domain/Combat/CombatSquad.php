<?php


namespace App\Domain\Combat;


use Illuminate\Support\Collection;

class CombatSquad implements CombatGroup
{
    public function getCombatHeroes()
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
