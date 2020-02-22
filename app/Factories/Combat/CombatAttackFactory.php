<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class CombatAttackFactory
{
    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $name = 'Test_Hero_Combat_Attack ' . rand(1, 99999);
        $attackID = rand(1, 999999);
        $attackerPosition = CombatPosition::query()->inRandomOrder()->first();
        $targetPosition = CombatPosition::query()->inRandomOrder()->first();
        $targetPriority = TargetPriority::query()->inRandomOrder()->first();
        $damageType = DamageType::query()->inRandomOrder()->first();
        $maxTargetCount = rand(3, 8);
        return new CombatAttack(
            $name,
            $attackID,
            100,
            10,
            10,
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $maxTargetCount
        );
    }
}
