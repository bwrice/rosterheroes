<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use Illuminate\Support\Collection;

interface CombatGroup
{
    public function getReadyAttacks(int $moment): Collection;

    public function getPossibleTargets($moment): CombatantCollection;
}