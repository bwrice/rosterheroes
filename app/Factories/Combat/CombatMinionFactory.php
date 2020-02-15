<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Combat\CombatMinion;
use App\Domain\Models\CombatPosition;

class CombatMinionFactory
{
    public static function new()
    {
        return new self();
    }

    public function create()
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->inRandomOrder()->first();

        return new CombatMinion(
            rand(1, 99999),
            1500,
            250,
            20,
            $combatPosition,
            collect()
        );
    }
}
