<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

interface CombatAttack
{
    public function getDamagePerTarget(int $targetsCount): int;

    public function getTargets(CombatantCollection $possibleTargets): CombatantCollection;
}
