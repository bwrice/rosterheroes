<?php


namespace App\Domain\Actions\Combat;

use App\Domain\Models\Attack;

class CalculateCombatDamage
{
    public function execute(Attack $attack, float $fantasyPower)
    {
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) max(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 1);
    }
}
