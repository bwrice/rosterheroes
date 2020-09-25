<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;

interface CombatAttackInterface
{
    public function getDamagePerTarget(int $targetsCount): int;

    public function getTargets(CombatantCollection $possibleTargets): CombatantCollection;
}
