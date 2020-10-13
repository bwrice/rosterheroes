<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use Illuminate\Support\Collection;

interface CombatGroup
{
    public function getPossibleTargets($moment): Collection;

    public function getPossibleAttackers($moment): Collection;

    public function isDefeated(int $moment): bool;
}
