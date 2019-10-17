<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior
{
    public function getSVG($attacker = true): string
    {
        return $attacker ? $this->getAttackerSVG() : $this->getTargetSVG();
    }


    abstract public function getAttackerSVG(): string;

    abstract public function getTargetSVG(): string;

    abstract public function getBaseDamageBonus(): float;

    abstract public function getCombatSpeedBonus(): float;

    abstract public function getDamageMultiplierBonus(): float;
}
