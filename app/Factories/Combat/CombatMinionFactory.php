<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Str;

class CombatMinionFactory extends AbstractCombatantFactory
{

    public function create()
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = $this->getCombatPosition();

        return new CombatMinion(
            (string) Str::uuid(),
            1500,
            250,
            20,
            $combatPosition,
            collect()
        );
    }
}
