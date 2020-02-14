<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class HeroCombatAttackFactory
{
    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $name = 'Test_Hero_Combat_Attack';
        $heroID = rand(1, 999999);
        $itemID = rand(1, 999999);
        $attackID = rand(1, 999999);
        $attackerPosition = CombatPosition::query()->inRandomOrder()->first();
        $targetPosition = CombatPosition::query()->inRandomOrder()->first();
        $targetPriority = TargetPriority::query()->inRandomOrder()->first();
        $damageType = DamageType::query()->inRandomOrder()->first();
        $resourceCosts = collect();
        $maxTargetCount = rand(3, 8);
        return new HeroCombatAttack(
            $name,
            $heroID,
            $itemID,
            $attackID,
            100,
            10,
            10,
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $resourceCosts,
            $maxTargetCount
        );
    }
}
