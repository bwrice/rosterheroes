<?php


namespace App\Domain\Behaviors\EnemyTypes;


class EnemyTypeBehavior
{
    protected $healthModifierBonus = 0;
    protected $protectionModifierBonus = 0;
    protected $blockModifierBonus = 0;
    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    /**
     * @return float
     */
    public function getHealthModifierBonus(): float
    {
        return $this->healthModifierBonus;
    }

    /**
     * @return float
     */
    public function getProtectionModifierBonus(): float
    {
        return $this->protectionModifierBonus;
    }

    /**
     * @return float
     */
    public function getBaseDamageModifierBonus(): float
    {
        return $this->baseDamageModifierBonus;
    }

    /**
     * @return float
     */
    public function getDamageMultiplierModifierBonus(): float
    {
        return $this->damageMultiplierModifierBonus;
    }

    /**
     * @return float
     */
    public function getCombatSpeedModifierBonus(): float
    {
        return $this->combatSpeedModifierBonus;
    }

    /**
     * @return int
     */
    public function getBlockModifierBonus(): int
    {
        return $this->blockModifierBonus;
    }
}
