<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Interfaces\HasFantasyPoints;
use App\Domain\Models\Attack;

class CalculateCombatDamage
{
    public function execute(Attack $attack, HasFantasyPoints $hasFantasyPoints)
    {
        $fantasyPower = $this->getFantasyPower($hasFantasyPoints->getFantasyPoints());
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) max(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 1);
    }

    protected function getFantasyPower(float $totalPoints)
    {
        $fantasyPower = 0;
        $coefficient = 1;
        $remaining = $totalPoints;
        while ($remaining > 0) {
            $pointsToMultiply = $remaining < 10 ? $remaining : 10;
            $fantasyPower += ($coefficient * $pointsToMultiply);
            $remaining -= 10;
            $coefficient *= .8;
        }
        return $fantasyPower;
    }
}
