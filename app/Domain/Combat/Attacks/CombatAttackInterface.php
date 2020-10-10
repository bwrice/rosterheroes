<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;

interface CombatAttackInterface
{
    public function getDamagePerTarget(int $targetsCount): int;

    public function getTargetPositionID(): int;

    public function getTargetPriorityID(): int;

    public function getDamageTypeID(): int;

    public function getTargetsCount(): ?int;

    public function getTier(): int;
}
