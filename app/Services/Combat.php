<?php


namespace App\Services;


class Combat
{
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
