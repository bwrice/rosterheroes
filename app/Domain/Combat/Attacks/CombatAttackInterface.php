<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

interface CombatAttackInterface
{
    public function getDamagePerTarget(int $targetsCount): int;

    public function getTargets(CombatantCollection $possibleTargets): CombatantCollection;
}
