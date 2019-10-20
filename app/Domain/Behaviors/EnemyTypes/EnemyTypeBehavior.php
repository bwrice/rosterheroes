<?php


namespace App\Domain\Behaviors\EnemyTypes;


class EnemyTypeBehavior
{
    protected $healthModifierBonus = 0;
    protected $protectionModifierBonus = 0;

    /**
     * @return int
     */
    public function getHealthModifierBonus(): int
    {
        return $this->healthModifierBonus;
    }

    /**
     * @return int
     */
    public function getProtectionModifierBonus(): int
    {
        return $this->protectionModifierBonus;
    }
}
