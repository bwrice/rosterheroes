<?php


namespace App\Domain\Combat;


use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

interface CombatAttack
{
    public function getDamagePerTarget(int $targetsCount): int;

    public function getDamageVariance(): float;

    public function getTargetCombatPositions(): Collection;

    public function getTargetPriority(): TargetPriority;

    public function getMaxTargets(): int;

    public function handleDamageGiven(int $damageGiven);
}
