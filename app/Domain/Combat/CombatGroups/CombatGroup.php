<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use Illuminate\Support\Collection;

interface CombatGroup
{
    public function updateCombatPositions(CombatPositionCollection $combatPositions);

    public function getReadyAttacks(int $moment): Collection;

    public function getPossibleTargets($moment): CombatantCollection;

    public function isDefeated(int $moment): bool;
}
