<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

interface CombatGroup
{
    public function updateCombatPositions(EloquentCollection $combatPositions);

    public function getReadyAttacks(int $moment): Collection;

    public function getPossibleTargets($moment): CombatantCollection;
}
