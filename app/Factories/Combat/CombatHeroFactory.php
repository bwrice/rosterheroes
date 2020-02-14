<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroFactory
{
    public static function new()
    {
        return new self();
    }

    public function create()
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->inRandomOrder()->first();

        return new CombatHero(
            rand(1, 999999),
            800,
            800,
            800,
            250,
            20,
            $combatPosition,
            collect()
        );
    }
}
