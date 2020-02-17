<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior
{
    protected $combatSpeedBonus = 0;
    protected $baseDamageBonus = 0;
    protected $damageMultiplierBonus = 0;
    protected $attackerSVG = '';
    protected $targetSVG = '';
    protected $proximity = 100;

    public function getSVG($attacker = true): string
    {
        return $attacker ? $this->getAttackerSVG() : $this->getTargetSVG();
    }

    public function getCombatSpeedBonus(): float
    {
        return $this->combatSpeedBonus;
    }

    public function getBaseDamageBonus(): float
    {
        return $this->baseDamageBonus;
    }

    public function getDamageMultiplierBonus(): float
    {
        return $this->damageMultiplierBonus;
    }

    public function getAttackerSVG(): string
    {
        return $this->attackerSVG;
    }

    public function getTargetSVG(): string
    {
        return $this->targetSVG;
    }

    public function getProximity(): int
    {
        return $this->proximity;
    }
}
