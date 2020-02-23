<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\CombatPosition;

class CombatMinionFactory extends AbstractCombatantFactory
{

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
