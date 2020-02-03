<?php


namespace App\Services\ModelServices;


use App\Domain\Models\Attack;

class AttackService
{
    public function all()
    {
        return Attack::all();
    }

    public function query()
    {
        return Attack::query();
    }

    /**
     * @param $baseDamage
     * @param $damageMultiplier
     * @param $fantasyPower
     * @return int
     */
    public function getDamage($baseDamage, $damageMultiplier, $fantasyPower)
    {
        return (int) min(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 0);
    }
}
